<?php

namespace App\Support;

class Installer
{
    /** Path to the lock file written after a successful install. */
    public static function lockFile(): string
    {
        return storage_path('app/installed.lock');
    }

    /** True when the app has been installed (lock file present). */
    public static function isInstalled(): bool
    {
        return is_file(self::lockFile());
    }

    /** Write the lock file with metadata about who/when. */
    public static function markInstalled(string $adminEmail): void
    {
        @file_put_contents(self::lockFile(), json_encode([
            'installed_at' => now()->toIso8601String(),
            'admin' => $adminEmail,
            'version' => '2.0',
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Update or insert key=value pairs in the .env file. Preserves
     * surrounding lines (comments, blank lines, other keys).
     */
    public static function writeEnv(array $values): void
    {
        $path = base_path('.env');
        if (! is_file($path)) {
            // No .env yet — bootstrap from example.
            if (is_file(base_path('.env.example'))) {
                @copy(base_path('.env.example'), $path);
            } else {
                @file_put_contents($path, '');
            }
        }

        $contents = (string) @file_get_contents($path);
        foreach ($values as $key => $value) {
            $escaped = self::escapeEnvValue((string) $value);
            $line = "{$key}={$escaped}";
            if (preg_match("/^{$key}=.*$/m", $contents)) {
                $contents = preg_replace("/^{$key}=.*$/m", $line, $contents);
            } else {
                $contents = rtrim($contents)."\n".$line."\n";
            }
        }

        @file_put_contents($path, $contents);
    }

    /** Quote env values when they contain spaces, hashes, or quotes. */
    protected static function escapeEnvValue(string $value): string
    {
        if ($value === '') return '';
        if (preg_match('/[\s#"\'=]/', $value)) {
            return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $value).'"';
        }
        return $value;
    }

    /** Run the requirements check; returns an associative array of label => pass:bool. */
    public static function requirements(): array
    {
        $checks = [
            'PHP ≥ 8.3' => version_compare(PHP_VERSION, '8.3.0', '>='),
        ];
        foreach (['mbstring', 'openssl', 'pdo', 'tokenizer', 'xml', 'json', 'bcmath', 'fileinfo', 'curl'] as $ext) {
            $checks["{$ext} extension"] = extension_loaded($ext);
        }
        $checks['storage/ writable'] = is_writable(storage_path());
        $checks['bootstrap/cache/ writable'] = is_writable(base_path('bootstrap/cache'));
        $checks['.env writable (or absent)'] = ! is_file(base_path('.env')) || is_writable(base_path('.env'));
        return $checks;
    }
}
