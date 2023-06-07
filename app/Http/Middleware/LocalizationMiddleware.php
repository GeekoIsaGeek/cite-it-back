<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
	public function handle(Request $request, Closure $next): Response
	{
		if ($request->hasHeader('Accept-Language')) {
			$locale = $request->header('Accept-Language');
			App::setLocale($locale);
		}
		return $next($request);
	}
}
