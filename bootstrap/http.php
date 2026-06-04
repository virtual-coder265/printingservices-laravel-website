<?php

/**
 * Normalize REQUEST_URI when the app runs behind a subdirectory (XAMPP folder,
 * shared hosting) or via a root index.php that forwards into public/.
 */
function normalizeRequestUriForSubdirectory(): void
{
    if (! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
        return;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $basePath = rtrim(dirname($scriptName), '/');

    if (str_ends_with($basePath, '/public')) {
        $basePath = substr($basePath, 0, -7);
    }

    if ($basePath === '' || $basePath === '/') {
        return;
    }

    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
    $query = parse_url($requestUri, PHP_URL_QUERY);

    if (str_starts_with($path, $basePath.'/public')) {
        $path = substr($path, strlen($basePath.'/public')) ?: '/';
    } elseif (str_starts_with($path, $basePath)) {
        $path = substr($path, strlen($basePath)) ?: '/';
    } else {
        return;
    }

    if ($path !== '/' && ! str_starts_with($path, '/')) {
        $path = '/'.$path;
    }

    $_SERVER['REQUEST_URI'] = $path.($query ? '?'.$query : '');
}
