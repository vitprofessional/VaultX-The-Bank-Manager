<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class EmployeeAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Session::has('superAdmin')
            || Session::has('generalAdmin')
            || Session::has('manager')
            || Session::has('cashier')
        ) {
            return $next($request);
        }

        return redirect(route('calculasLogin'))->with('error', 'Please login to continue');
    }
}
