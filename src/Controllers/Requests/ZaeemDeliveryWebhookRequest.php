<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZaeemDeliveryWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'system_code' => ['required', 'string'],
            'updates' => ['required', 'array', 'min:1'],
            'updates.*.shipment_number' => ['required', 'string'],
            'updates.*.shipment_id' => ['nullable', 'integer'],
            'updates.*.external_id' => ['nullable', 'string'],
            'updates.*.action_code' => ['nullable', 'string'],
            'updates.*.current_step' => ['nullable', 'string'],
            'updates.*.current_step_ar' => ['nullable', 'string'],
            'updates.*.current_stage' => ['nullable', 'string'],
            'updates.*.current_stage_ar' => ['nullable', 'string'],
            'updates.*.governorate_code' => ['nullable', 'string'],
            'updates.*.governorate_name' => ['nullable', 'string'],
            'updates.*.note' => ['nullable', 'string'],
            'updates.*.agent_latitude' => ['nullable', 'numeric'],
            'updates.*.agent_longitude' => ['nullable', 'numeric'],
            'updates.*.amount_iqd' => ['nullable', 'numeric'],
            'updates.*.amount_usd' => ['nullable', 'numeric'],
            'updates.*.quantity_delivered' => ['nullable', 'integer'],
            'updates.*.quantity_returned' => ['nullable', 'integer'],
            'updates.*.postponed_reason' => ['nullable', 'string'],
            'updates.*.postponed_reason_en' => ['nullable', 'string'],
            'updates.*.postponed_reason_ku' => ['nullable', 'string'],
            'updates.*.postponed_date_id' => ['nullable', 'integer'],
            'updates.*.return_reason' => ['nullable', 'string'],
            'updates.*.return_reason_en' => ['nullable', 'string'],
            'updates.*.return_reason_ku' => ['nullable', 'string'],
        ];
    }
}
