<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryReceived;
use App\Models\Inquiry;
use App\Models\Package;
use App\Rules\Turnstile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'trip' => ['nullable', 'string', 'exists:packages,slug'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'start_date' => ['nullable', 'date'],
            'group_size' => ['nullable', 'integer', 'min:1', 'max:60'],
            'message' => ['nullable', 'string', 'max:2000'],
            Turnstile::FIELD => [new Turnstile($request->ip())],
        ]);

        $package = isset($data['trip'])
            ? Package::where('slug', $data['trip'])->first()
            : null;

        $inquiry = Inquiry::create([
            'reference' => Inquiry::makeReference($package?->category),
            'package_id' => $package?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'group_size' => $data['group_size'] ?? 1,
            'message' => $data['message'] ?? null,
            'status' => 'new',
        ]);

        try {
            Mail::to(config('mail.inquiry_notify'))->send(new NewInquiryReceived($inquiry));
        } catch (\Throwable $e) {
            // The inquiry is already saved — a mail outage should never block
            // the customer's confirmation, just get logged for follow-up.
            Log::error('Failed to send inquiry notification email', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['reference' => $inquiry->reference]);
    }
}
