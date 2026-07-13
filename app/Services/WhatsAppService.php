<?php

namespace App\Services;

use App\Models\WhatsAppSettings;

class WhatsAppService
{
    protected ?WhatsAppSettings $settings = null;

    public function __construct()
    {
        $this->settings = WhatsAppSettings::where('active', true)->first();
    }

    public function isConfigured(): bool
    {
        return $this->settings !== null
            && !empty($this->settings->account_id)
            && !empty($this->settings->phone_number_id)
            && !empty($this->settings->token);
    }

    public function sendMessage(string $to, string $body): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        // TODO: Implementar llamada a API de WhatsApp Business
        // POST https://graph.facebook.com/v18.0/{phone-number-id}/messages
        return false;
    }

    public function verifyWebhook(string $token): bool
    {
        if (!$this->settings) {
            return false;
        }
        return $token === $this->settings->webhook_verify_token;
    }
}
