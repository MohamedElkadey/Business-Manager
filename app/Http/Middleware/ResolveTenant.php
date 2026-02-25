<?php

namespace App\Http\Middleware;

use App\Tenancy\TenantContext;
use Closure;
use DomainException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if(!$user){
            throw new DomainException('Unauthenticated.');
        } 
        $company = $user->company;
        if (!$company) {
            throw new UnauthorizedHttpException('', 'User has no company.');
        }
        app(TenantContext::class)->set($company->id, $company->uuid);

        return $next($request);
    }
}
