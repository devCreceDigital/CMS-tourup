<?php

namespace App\Services;

use App\Models\NotificationRule;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationEngine
{
    public function evaluate(string $event, Customer $customer, array $context = []): void
    {
        $rules = NotificationRule::active()
            ->where('event', $event)
            ->with('template')
            ->get();

        foreach ($rules as $rule) {
            if ($this->conditionsMet($rule, $context)) {
                $this->dispatch($rule, $customer, $context);
            }
        }
    }

    protected function conditionsMet(NotificationRule $rule, array $context): bool
    {
        if (empty($rule->conditions)) {
            return true;
        }

        foreach ($rule->conditions as $field => $expected) {
            $actual = $context[$field] ?? null;
            if ($actual != $expected) {
                return false;
            }
        }

        return true;
    }

    protected function dispatch(NotificationRule $rule, Customer $customer, array $context): void
    {
        $template = $rule->template;
        if (!$template) {
            return;
        }

        $body = $this->renderTemplate($template->body, $customer, $context);
        $subject = $template->subject
            ? $this->renderTemplate($template->subject, $customer, $context)
            : null;

        if (in_array($rule->channel, ['email', 'ambos']) && $customer->email) {
            $this->sendEmail($customer->email, $subject ?? 'Notificación', $body);
        }

        if (in_array($rule->channel, ['whatsapp', 'ambos']) && $customer->phone) {
            $this->sendWhatsApp($customer->phone, $body);
        }
    }

    protected function renderTemplate(string $text, Customer $customer, array $context): string
    {
        $replacements = [
            '{customer_name}' => $customer->name,
            '{customer_email}' => $customer->email,
            '{customer_phone}' => $customer->phone,
            '{stage}' => Customer::STAGES[$customer->stage] ?? $customer->stage,
        ];

        foreach ($context as $key => $value) {
            $replacements['{' . $key . '}'] = (string) $value;
        }

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    protected function sendEmail(string $to, string $subject, string $body): void
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            Log::error("NotificationEngine: Error enviando email a {$to}: {$e->getMessage()}");
        }
    }

    protected function sendWhatsApp(string $to, string $body): void
    {
        Log::info("NotificationEngine: WhatsApp a {$to}: {$body}");
    }
}
