<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->current_team_id !== null;
    }

    public function rules(): array
    {
        $teamId = $this->user()->current_team_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'birthday' => ['nullable', 'date'],
            'lifecycle_stage' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'group_id' => ['nullable', Rule::exists('groups', 'id')->where('team_id', $teamId)],
            'tags' => ['nullable', 'array'],
            'tags.*' => [Rule::exists('tags', 'id')->where('team_id', $teamId)],
            'custom_fields_keys' => ['nullable', 'array'],
            'custom_fields_keys.*' => ['nullable', 'string', 'max:80'],
            'custom_fields_values' => ['nullable', 'array'],
            'custom_fields_values.*' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
