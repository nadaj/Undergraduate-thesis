<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        if ($request->user() === null) 
        {
            return redirect()->route('login');
        }

        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        
        if ($request->user()->hasRole($roles) || !$roles) {
            return $next($request);
        }

        return redirect('error')->with('fail', 'Nije validan zahtev!');
    }
}
