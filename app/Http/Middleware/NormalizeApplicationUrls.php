<?php

namespace App\Http\Middleware;

use App\Support\ApplicationUrl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class NormalizeApplicationUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
        }

        if ($intended = $request->session()->get('url.intended')) {
            $request->session()->put('url.intended', ApplicationUrl::for($intended));
        }

        return $next($request);
    }
}
