<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class SessionKey
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
        if($request->headers->has('session-key')){
            header('session-key: '.$request->headers->get('session-key'));
        }else{
            header('session-key: '.md5(Str::random(60)));
        }
        return $next($request);
    }
}
