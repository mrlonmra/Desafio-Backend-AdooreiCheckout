<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdooreiAPI
{
    /**
     * Sistema de autenticação para a API.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-TOKEN-ADOOREI-API');

        if (!$this->tokenValid($token)) {
            return response()->json(['error' => 'ACESSO NÃO AUTORIZADO'], 401);
        }

        return $next($request);
    }

    public function tokenValid($token)
    {
        return $token === "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9";
    }
}
