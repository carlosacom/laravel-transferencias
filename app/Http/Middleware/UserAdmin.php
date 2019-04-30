<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class UserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find($request->header('DataUser')->sub);
        if (strcmp($user->role->name, 'Administrador') === 0) {
            return $next($request);
        } else {
            return response()->json(['errors' => 'Rol no permitido'], 400);
        }
    }
}
