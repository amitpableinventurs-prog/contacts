<?php

namespace App\Support;

class ColorHelper
{
    /**
     * Convert a #rrggbb hex string to an HSL token like "263 70% 50%" suitable
     * for use with hsl(var(--primary)) Tailwind colors.
     */
    public static function hexToHslVar(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '263 70% 50%';

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $h = $s = 0;

        if ($max !== $min) {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            $h = match (true) {
                $max === $r => ($g - $b) / $d + ($g < $b ? 6 : 0),
                $max === $g => ($b - $r) / $d + 2,
                default     => ($r - $g) / $d + 4,
            };
            $h *= 60;
        }

        return round($h).' '.round($s * 100).'% '.round($l * 100).'%';
    }
}
