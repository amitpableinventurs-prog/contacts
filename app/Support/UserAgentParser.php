<?php

namespace App\Support;

final class UserAgentParser
{
    public static function browser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/')       => 'Edge',
            str_contains($ua, 'OPR/')
                || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Chrome/')    => 'Chrome',
            str_contains($ua, 'Firefox/')   => 'Firefox',
            str_contains($ua, 'Safari/')    => 'Safari',
            str_contains($ua, 'MSIE')
                || str_contains($ua, 'Trident/') => 'Internet Explorer',
            default                         => 'Unknown',
        };
    }

    public static function platform(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Windows NT') => 'Windows',
            str_contains($ua, 'Mac OS X')   => 'macOS',
            str_contains($ua, 'Linux')      => 'Linux',
            str_contains($ua, 'Android')    => 'Android',
            str_contains($ua, 'iPhone')
                || str_contains($ua, 'iPad') => 'iOS',
            default                          => 'Unknown',
        };
    }

    public static function device(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPhone')   => 'Mobile',
            str_contains($ua, 'Android') && str_contains($ua, 'Mobile') => 'Mobile',
            str_contains($ua, 'iPad')     => 'Tablet',
            str_contains($ua, 'Android') => 'Tablet',
            default                       => 'Desktop',
        };
    }
}
