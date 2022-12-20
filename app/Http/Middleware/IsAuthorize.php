<?php

namespace App\Http\Middleware;

use Closure;

class IsAuthorize
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
      $user =  session("user");
      if (!$user) {
          return redirect()->route('login')->with([
            "message" => "Unauthorize access is prohibited!"
          ]);
      }
        return $next($request);
    }
}
