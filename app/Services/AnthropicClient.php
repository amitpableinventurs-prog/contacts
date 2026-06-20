<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AnthropicClient
{
    public function isFake(): bool
    {
        return (bool) config('anthropic.fake');
    }

    /**
     * Extract contact fields from arbitrary text (email signature, LinkedIn
     * paste, etc.). Returns an associative array matching the contact fields.
     */
    public function extractContact(string $text): array
    {
        if ($this->isFake()) {
            return $this->fakeExtraction($text);
        }

        $tool = [
            'name' => 'save_contact',
            'description' => 'Save the extracted contact details.',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string'],
                    'email' => ['type' => 'string'],
                    'phone' => ['type' => 'string'],
                    'company' => ['type' => 'string'],
                    'job_title' => ['type' => 'string'],
                    'website' => ['type' => 'string'],
                    'linkedin' => ['type' => 'string'],
                    'notes' => ['type' => 'string'],
                ],
                'required' => ['name'],
            ],
        ];

        $payload = [
            'model' => config('anthropic.model'),
            'max_tokens' => config('anthropic.max_tokens'),
            'tools' => [$tool],
            'tool_choice' => ['type' => 'tool', 'name' => 'save_contact'],
            'messages' => [[
                'role' => 'user',
                'content' => "Extract contact details from this text. Use empty strings for unknown fields.\n\n---\n{$text}",
            ]],
        ];

        $client = new Client(['base_uri' => config('anthropic.base_url').'/']);
        $res = $client->post('messages', [
            'headers' => [
                'x-api-key' => config('anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        $body = json_decode((string) $res->getBody(), true);

        foreach (($body['content'] ?? []) as $block) {
            if (($block['type'] ?? null) === 'tool_use' && ($block['name'] ?? null) === 'save_contact') {
                return $block['input'] ?? [];
            }
        }

        Log::warning('Anthropic returned no tool_use', ['body' => $body]);
        return [];
    }

    /**
     * Fix spelling, grammar, and clarity. Returns the corrected text.
     */
    public function spellCheck(string $text): string
    {
        if ($this->isFake()) {
            return $this->fakeSpellCheck($text);
        }

        $payload = [
            'model' => config('anthropic.model'),
            'max_tokens' => 1024,
            'system' => 'You are a copy editor. Return ONLY the corrected text — no commentary, no quotes, no explanations. Preserve the writer\'s voice; fix spelling, grammar, and obvious typos.',
            'messages' => [['role' => 'user', 'content' => $text]],
        ];

        return $this->extractText($this->callApi($payload), $text);
    }

    /**
     * Translate text to the target language. Returns translated text.
     */
    public function translate(string $text, string $targetLanguage = 'English'): string
    {
        if ($this->isFake()) {
            return "[".strtoupper($targetLanguage)."] ".$text;
        }

        $payload = [
            'model' => config('anthropic.model'),
            'max_tokens' => 1024,
            'system' => "Translate the user's message to {$targetLanguage}. Return ONLY the translation — no commentary, no notes, no quotes. Preserve tone (casual stays casual, formal stays formal).",
            'messages' => [['role' => 'user', 'content' => $text]],
        ];

        return $this->extractText($this->callApi($payload), $text);
    }

    protected function callApi(array $payload): array
    {
        $client = new Client(['base_uri' => config('anthropic.base_url').'/']);
        $res = $client->post('messages', [
            'headers' => [
                'x-api-key' => config('anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ],
            'json' => $payload,
        ]);
        return json_decode((string) $res->getBody(), true) ?: [];
    }

    protected function extractText(array $response, string $fallback): string
    {
        foreach (($response['content'] ?? []) as $block) {
            if (($block['type'] ?? null) === 'text') {
                return trim($block['text']);
            }
        }
        return $fallback;
    }

    /**
     * Suggest up to 5 relevant tag slugs from the team's available set.
     * Returns array of slugs (matched against existing tags).
     */
    public function suggestTags(string $text, array $availableTags): array
    {
        if (empty($availableTags)) return [];

        if ($this->isFake()) {
            return $this->fakeSuggestTags($text, $availableTags);
        }

        $slugs = array_values(array_unique(array_column($availableTags, 'slug')));
        $tagList = collect($availableTags)
            ->map(fn ($t) => "- {$t['slug']}: {$t['name']}")
            ->implode("\n");

        $tool = [
            'name' => 'suggest_tags',
            'description' => 'Pick up to 5 of the most relevant tags for this contact. Empty array if none clearly apply.',
            'input_schema' => [
                'type' => 'object',
                'properties' => [
                    'tags' => [
                        'type' => 'array',
                        'items' => ['type' => 'string', 'enum' => $slugs],
                        'maxItems' => 5,
                    ],
                ],
                'required' => ['tags'],
            ],
        ];

        $payload = [
            'model' => $this->model(),
            'max_tokens' => 256,
            'tools' => [$tool],
            'tool_choice' => ['type' => 'tool', 'name' => 'suggest_tags'],
            'messages' => [[
                'role' => 'user',
                'content' => "Suggest up to 5 most relevant tags. Only pick ones that clearly apply.\n\nAvailable tags:\n{$tagList}\n\nText:\n---\n{$text}",
            ]],
        ];

        $body = $this->callApi($payload);
        foreach (($body['content'] ?? []) as $block) {
            if (($block['type'] ?? null) === 'tool_use' && ($block['name'] ?? null) === 'suggest_tags') {
                return $block['input']['tags'] ?? [];
            }
        }
        return [];
    }

    /** Match tag name or slug appearing in text + synonym heuristics. */
    protected function fakeSuggestTags(string $text, array $availableTags): array
    {
        $lower = mb_strtolower($text);
        $synonyms = [
            'vip' => ['important', 'priority', 'high-value', 'high value'],
            'hot' => ['urgent', 'high-intent', 'ready to buy'],
            'follow-up' => ['call back', 'circle back', 'follow up', 'followup', 'next steps'],
            'cold' => ['low priority', 'parked', 'waiting'],
            'enterprise' => ['enterprise', 'fortune 500', 'large account'],
            'referral' => ['referred', 'introduction', 'intro from'],
        ];

        $suggested = [];
        foreach ($availableTags as $t) {
            $slug = $t['slug'];
            $needles = array_filter([mb_strtolower($t['name']), $slug, ...($synonyms[$slug] ?? [])]);
            foreach ($needles as $n) {
                if ($n && str_contains($lower, $n)) {
                    $suggested[] = $slug;
                    break;
                }
            }
        }
        return array_values(array_unique($suggested));
    }

    /**
     * Naive spell-check fallback for fake mode — common typos only.
     */
    protected function fakeSpellCheck(string $text): string
    {
        $fixes = [
            '/\bteh\b/i' => 'the',
            '/\brecieve\b/i' => 'receive',
            '/\bseperate\b/i' => 'separate',
            '/\bdefinately\b/i' => 'definitely',
            '/\boccured\b/i' => 'occurred',
            '/\baccomodate\b/i' => 'accommodate',
            '/\btommorow\b/i' => 'tomorrow',
            '/\balot\b/i' => 'a lot',
            '/\bwierd\b/i' => 'weird',
            '/  +/' => ' ',
        ];
        $fixed = preg_replace(array_keys($fixes), array_values($fixes), $text);
        $fixed = preg_replace_callback('/(^|[.!?]\s+)([a-z])/', fn ($m) => $m[1].strtoupper($m[2]), $fixed);
        return trim($fixed);
    }

    /**
     * Mock extractor for fake mode. Pulls obvious fields via regex.
     */
    protected function fakeExtraction(string $text): array
    {
        $out = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'company' => '',
            'job_title' => '',
            'website' => '',
            'linkedin' => '',
            'notes' => '',
        ];

        if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $text, $m)) {
            $out['email'] = $m[0];
        }
        if (preg_match('/(?:\+?\d{1,3}[\s.-]?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}/', $text, $m)) {
            $out['phone'] = $m[0];
        }
        if (preg_match('~https?://(?:www\.)?linkedin\.com/in/[\w\-]+~i', $text, $m)) {
            $out['linkedin'] = $m[0];
        }
        if (preg_match('~https?://[^\s,;<>"\'<]+~i', $text, $m)) {
            $out['website'] = $m[0];
        }

        $lines = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $text))));
        if (! empty($lines)) {
            // First non-empty line is often the name.
            if (preg_match('/^[A-Z][\w.\'-]+(?:\s+[A-Z][\w.\'-]+)+$/', $lines[0])) {
                $out['name'] = $lines[0];
            }
            // Second line often the title at company.
            if (count($lines) >= 2 && preg_match('/^(.+?)\s+(?:at|@|,)\s+(.+)$/i', $lines[1], $m)) {
                $out['job_title'] = trim($m[1]);
                $out['company'] = trim($m[2]);
            }
        }

        return $out;
    }
}
