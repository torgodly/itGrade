<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->type === 'teacher' && !auth()->user()->is_approved) {
            Notification::make('access_denied')
                ->title(__('Access Denied'))
                ->body(__('You cannot access the admin panel until your account is approved by an administrator.'))
                ->danger()
                ->send();

            auth()->logout();

            return redirect('/admin/login'); // return here
        }

        return $next($request);
    }

}
