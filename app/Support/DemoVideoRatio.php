<?php

namespace App\Support;

final class DemoVideoRatio
{
    public const LANDSCAPE = 'landscape';
    public const REEL = 'reel';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return [
            self::LANDSCAPE,
            self::REEL,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::LANDSCAPE => 'Landscape',
            self::REEL => 'Reel',
        ];
    }

    public static function normalize(?string $value, string $default = self::LANDSCAPE): string
    {
        return in_array($value, self::values(), true) ? $value : $default;
    }

    public static function label(?string $value, string $default = self::LANDSCAPE): string
    {
        $normalized = self::normalize($value, $default);

        return self::options()[$normalized];
    }
}
