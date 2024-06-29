<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Auth\Access\AuthorizationException;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Exception\BadRequestException;
	use Symfony\Component\HttpFoundation\Response;
	
	class ApiKeyMiddleware {
		/**
		 * Handle an incoming request.
		 *
		 * @param  Closure(Request): (Response)  $next
		 *
		 * @throws AuthorizationException
		 */
		public function handle(Request $request, Closure $next): Response {
			// read api key from header "X-API-KEY"
			$apiKey = $request->header('X-API-KEY');
			
			if ( !$apiKey || $apiKey !== env('API_KEY')) {
				throw new AuthorizationException("You are unauthorized. Please check your API key.");
			}
			
			return $next($request);
		}
	}
