<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
   public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        // Si ya estaba verificado, devolvemos un 200 OK
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified'], 200);
        }

        // Marcamos como verificado y disparamos el evento
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Respondemos con éxito para que React muestre la pantalla verde
        return response()->json(['message' => 'Verified successfully'], 200);
    }
}
