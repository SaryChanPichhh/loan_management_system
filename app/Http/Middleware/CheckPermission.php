<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }

        $user = Auth::user();

        // If no specific permission string is passed, default to checking the current route name
        if (!$permission) {
            $permission = $request->route()->getName();
        }

        // Skip checking if there's no named route or permission to check
        if (!$permission) {
            return $next($request);
        }

        // Grant full permission if the user is 'setec'
        if ($user->username === 'setec') {
            return $next($request);
        }

        // Check if the user has the required permission (direct or via role)
        if (!$user->hasPermission($permission)) {
            // Abort with 403 Forbidden if they don't have permission
            abort(403, "អ្នកមិនមានសិទ្ធិចូលមើលទំព័រនេះទេ។");
        }

        return $next($request);
    }
}
