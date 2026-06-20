<?php

namespace App\Support;

use App\Models\User;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactor
{
    public static function engine(): Google2FA
    {
        return new Google2FA();
    }

    /**
     * Generate a new TOTP secret + recovery codes and stash them (unconfirmed)
     * on the user. The user must verify a code from their authenticator before
     * we mark 2FA as actually enabled (two_factor_confirmed_at).
     */
    public static function generate(User $user): void
    {
        $g = self::engine();
        $recoveryCodes = collect(range(1, 8))->map(fn () => Str::lower(Str::random(10)))->all();

        $user->forceFill([
            'two_factor_secret' => Crypt::encryptString($g->generateSecretKey()),
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public static function secret(User $user): ?string
    {
        return $user->two_factor_secret
            ? Crypt::decryptString($user->two_factor_secret)
            : null;
    }

    public static function recoveryCodes(User $user): array
    {
        return $user->two_factor_recovery_codes
            ? json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true)
            : [];
    }

    public static function consumeRecoveryCode(User $user, string $code): bool
    {
        $codes = self::recoveryCodes($user);
        $code = Str::lower(trim($code));
        if (! in_array($code, $codes, true)) {
            return false;
        }
        $remaining = array_values(array_diff($codes, [$code]));
        $user->forceFill([
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($remaining)),
        ])->save();
        return true;
    }

    public static function qrCodeSvg(User $user, string $issuer = 'LaraContact'): string
    {
        $g = self::engine();
        $secret = self::secret($user);
        $url = $g->getQRCodeUrl($issuer, $user->email, $secret);

        $renderer = new ImageRenderer(
            new RendererStyle(220, 1),
            new SvgImageBackEnd()
        );
        return (new Writer($renderer))->writeString($url);
    }

    public static function verify(User $user, string $code): bool
    {
        $secret = self::secret($user);
        if (! $secret) return false;
        // window=2 → tolerate ±60 seconds of clock drift between server and authenticator.
        return self::engine()->verifyKey($secret, trim($code), 2);
    }
}
