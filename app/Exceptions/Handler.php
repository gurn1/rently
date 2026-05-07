<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;

class Handler extends Exception
{
    public function register(): void
    {
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403 && Auth::check()) {
                $user = Auth::user();

                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard')
                        ->with('error', 'You do not have access to that area.');
                } elseif ($user->hasRole('property_manager')) {
                    return redirect()->route('manager.dashboard')
                        ->with('error', 'You do not have access to that area.');
                } elseif ($user->hasRole('tenant')) {
                    return redirect()->route('tenant.dashboard')
                        ->with('error', 'You do not have access to that area.');
                }
            }
        });
    }
}
