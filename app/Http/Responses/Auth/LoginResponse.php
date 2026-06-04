<?php

namespace App\Http\Responses\Auth;

use App\Support\ApplicationUrl;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $default = ApplicationUrl::for(
            Filament::getUrl() ?? '/'.ltrim(Filament::getCurrentPanel()->getPath(), '/')
        );

        $intended = session()->pull('url.intended', $default);

        return redirect()->to(ApplicationUrl::for($intended) ?? $default);
    }
}
