<?php

namespace App\Http\Middleware;

use App\Tenancy\TenantContext;
use Closure;
use DomainException;
use Illuminate\Http\Request;
use Pos_SessionService;
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
        }//ToDo
        app(TenantContext::class)->set($company->id, $company->uuid);
        app(TenantContext::class)->setuser($user->id);
        $pos_session = app(Pos_SessionService::class)->getOrCreateActiveSession($company->id,$user->id,$request['device_uuid']);
        app(TenantContext::class)->setPosSession($pos_session);
        return $next($request);
    }
}
