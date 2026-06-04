<?php

namespace App\Support;

class ApplicationUrl
{
    public static function root(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    public static function for(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $root = static::root();

        if ($root === '') {
            return $url;
        }

        if (str_starts_with($url, $root.'/') || $url === $root) {
            return $url;
        }

        if (preg_match('#^https?://[^/]+(/.*)$#', $url, $matches)) {
            return $root.$matches[1];
        }

        if (str_starts_with($url, '/')) {
            return $root.$url;
        }

        return $root.'/'.ltrim($url, '/');
    }
}
