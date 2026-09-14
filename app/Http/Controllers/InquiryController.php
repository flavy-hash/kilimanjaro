<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return response()->json(['reference' => $inquiry->reference]);
    }
}
