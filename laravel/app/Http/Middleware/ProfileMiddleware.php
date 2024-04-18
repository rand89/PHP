<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = new User();
        if(!$user->find($request->id)) {
            return redirect(route('index'));
        }

        if(!$user->isAdmin()) {
            if(!$user->is_equal($request->id)) {
                $request->session()->flash('error', 'Можно редактировать только свой профиль.');
                return redirect(route('index'));
            }
        }

        return $next($request);
    }
}
