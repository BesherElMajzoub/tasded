<?php

namespace App\Http\Controllers;

use App\Models\ContactClick;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactClickController extends Controller
{
    /**
     * Record a WhatsApp/phone click with its ad attribution so qualified leads
     * can later be uploaded to Google Ads as offline conversions.
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'ref' => ['required', 'string', 'regex:/^[A-Z0-9]{6}$/'],
            'channel' => ['required', Rule::in(['whatsapp', 'phone'])],
            'variant' => ['nullable', 'string', 'max:50'],
            'page_path' => ['nullable', 'string', 'max:200'],
            ...array_fill_keys(ContactClick::ATTRIBUTION_FIELDS, ['nullable', 'string', 'max:200']),
        ]);

        if ($validator->fails()) {
            return response()->noContent(422);
        }

        $data = $validator->validated();

        ContactClick::firstOrCreate(['ref' => $data['ref']], $data);

        return response()->noContent();
    }
}
