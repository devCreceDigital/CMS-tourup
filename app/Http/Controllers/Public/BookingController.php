<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\PricingGroup;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    private function getTheme(): string
    {
        $agency = Agency::first();
        return request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');
    }

    private function getTrip(string $slug): Trip
    {
        $trip = Trip::where('slug', $slug)
            ->with(['category', 'pricingGroups.installments', 'accommodations', 'itineraryDays'])
            ->firstOrFail();

        if (Setting::get('booking_enabled', 'true') !== 'true') {
            abort(403, 'Las reservas están temporalmente deshabilitadas.');
        }

        return $trip;
    }

    public function step1(string $slug)
    {
        $trip = $this->getTrip($slug);
        $theme = $this->getTheme();
        $agency = Agency::first();
        return view("themes.{$theme}.public.booking.step1-date", compact('trip', 'agency'));
    }

    public function postStep1(Request $request, string $slug)
    {
        $trip = $this->getTrip($slug);
        $request->session()->put('booking_data', [
            'trip_id' => $trip->id,
            'trip_slug' => $slug,
            'reference' => (string) Str::uuid(),
        ]);
        return redirect()->route('public.booking.step2', $slug);
    }

    public function step2(string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        if (!$data || ($data['trip_slug'] ?? null) !== $slug) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $theme = $this->getTheme();
        $agency = Agency::first();
        $availableSpots = $trip->total_spots - $trip->occupied_spots;
        return view("themes.{$theme}.public.booking.step2-spots", compact('trip', 'agency', 'availableSpots'));
    }

    public function postStep2(Request $request, string $slug)
    {
        $trip = $this->getTrip($slug);
        $validated = $request->validate([
            'spots' => 'required|integer|min:1|max:' . ($trip->total_spots - $trip->occupied_spots),
        ]);

        $data = session('booking_data', []);
        $data['spots'] = $validated['spots'];
        $request->session()->put('booking_data', $data);

        return redirect()->route('public.booking.step3', $slug);
    }

    public function step3(string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        if (!$data || !isset($data['spots'])) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $theme = $this->getTheme();
        $agency = Agency::first();
        return view("themes.{$theme}.public.booking.step3-traveler-form", compact('trip', 'agency', 'data'));
    }

    public function postStep3(Request $request, string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        $spots = $data['spots'] ?? 1;

        $rules = [];
        for ($i = 0; $i < $spots; $i++) {
            $rules["travelers.$i.first_name"] = 'required|string|max:255';
            $rules["travelers.$i.last_name"] = 'nullable|string|max:255';
            $rules["travelers.$i.dni"] = 'required|string|max:20';
            $rules["travelers.$i.email"] = 'nullable|email|max:255';
            $rules["travelers.$i.phone"] = 'nullable|string|max:20';
            $rules["travelers.$i.birth_date"] = 'nullable|date';
            $rules["travelers.$i.sex"] = 'nullable|in:male,female,other';
        }

        $validated = $request->validate($rules);

        $data['travelers'] = $validated['travelers'];
        $request->session()->put('booking_data', $data);

        return redirect()->route('public.booking.step4', $slug);
    }

    public function step4(string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        if (!$data || !isset($data['travelers'])) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $theme = $this->getTheme();
        $agency = Agency::first();
        return view("themes.{$theme}.public.booking.step4-pricing", compact('trip', 'agency', 'data'));
    }

    public function postStep4(Request $request, string $slug)
    {
        $trip = $this->getTrip($slug);
        $validated = $request->validate([
            'pricing_group_id' => 'required|exists:pricing_groups,id',
        ]);

        $data = session('booking_data', []);
        $data['pricing_group_id'] = $validated['pricing_group_id'];
        $request->session()->put('booking_data', $data);

        return redirect()->route('public.booking.step5', $slug);
    }

    public function step5(string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        if (!$data || !isset($data['pricing_group_id'])) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $theme = $this->getTheme();
        $agency = Agency::first();
        return view("themes.{$theme}.public.booking.step5-extras", compact('trip', 'agency', 'data'));
    }

    public function postStep5(Request $request, string $slug)
    {
        $data = session('booking_data', []);
        $data['extras'] = $request->input('extras', []);
        $request->session()->put('booking_data', $data);

        return redirect()->route('public.booking.step6', $slug);
    }

    public function step6(string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = session('booking_data');
        if (!$data || !isset($data['extras'])) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $theme = $this->getTheme();
        $agency = Agency::first();

        $pricingGroup = isset($data['pricing_group_id'])
            ? PricingGroup::with('installments')->find($data['pricing_group_id'])
            : null;

        return view("themes.{$theme}.public.booking.step6-summary", compact('trip', 'agency', 'data', 'pricingGroup'));
    }

    public function confirm(Request $request, string $slug)
    {
        $trip = $this->getTrip($slug);
        $data = $request->session()->pull('booking_data');

        if (!$data || !isset($data['travelers'])) {
            return redirect()->route('public.booking.step1', $slug);
        }

        $validated = $request->validate([
            'accept_terms' => 'required|accepted',
        ]);

        try {
            $booking = $this->bookingService->createBooking($data);
            $this->bookingService->sendConfirmationEmails($booking);

            $request->session()->put('booking_confirmed', [
                'reference' => $booking->reference,
                'trip_name' => $trip->name,
                'trip_slug' => $slug,
                'status' => $booking->booking_status,
            ]);

            return redirect()->route('public.booking.step8', $slug);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function step8(string $slug)
    {
        $confirmed = session('booking_confirmed');
        if (!$confirmed || ($confirmed['trip_slug'] ?? null) !== $slug) {
            return redirect()->route('public.booking.step1', $slug);
        }
        $trip = $this->getTrip($slug);
        $theme = $this->getTheme();
        $agency = Agency::first();
        return view("themes.{$theme}.public.booking.step8-confirmation", compact('trip', 'agency', 'confirmed'));
    }
}
