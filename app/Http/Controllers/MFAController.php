<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

class MFAController extends Controller
{
    public function setup()
    {
        $user = Auth::user();

        if (!$user) {
            \Log::warning('❌ [SETUP] Utilisateur non connecté');
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        $user->mfaSecret()?->delete();

        $secret = app('pragmarx.google2fa')->generateSecretKey();
        \Log::info("🔐 [SETUP] Nouveau secret généré pour {$user->email} : {$secret}");

        $user->mfaSecret()->create([
            'secret' => $secret,
            'is_verified' => false,
        ]);

        $QR = app('pragmarx.google2fa')->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'qr_code' => $QR,
        ]);
    }

    public function verify(Request $request)
    {
        \Log::info('🔐 [VERIFY] Début vérification MFA');

        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user && session()->has('mfa_temp_user')) {
            $data = session()->get('mfa_temp_user');

            $user = Users::firstOrCreate(
                ['email' => $data['email']],
                ['password' => bcrypt(\Illuminate\Support\Str::random(16))]
            );

            Auth::login($user);
            session()->forget('mfa_temp_user');

            \Log::info("👤 [VERIFY] Utilisateur temporaire connecté : {$user->email}");
        }

        if (!$user) {
            \Log::error('❌ [VERIFY] Aucun utilisateur authentifié');
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        if (!$user->mfaSecret) {
            \Log::error('❌ [VERIFY] Aucun secret MFA trouvé pour l’utilisateur');
            return response()->json(['message' => 'Aucun secret MFA'], 400);
        }

        $secret = $user->mfaSecret->secret;

        \Log::info("🔐 [VERIFY] Secret utilisé : {$secret}");
        \Log::info("📨 [VERIFY] Code reçu : " . $request->code);

        $isValid = app('pragmarx.google2fa')->verifyKey($secret, $request->code, 2);

        if (!$isValid) {
            \Log::warning("⚠️ [VERIFY] Code invalide pour l’utilisateur {$user->email}");
            return response()->json(['message' => 'Code invalide ou expiré'], 401);
        }

        $user->mfaSecret->update(['is_verified' => true]);
        $user->authentification_2FA = true;
        $user->save();

        \Log::info("✅ [VERIFY] Code MFA validé pour {$user->email}");

        return response()->json(['message' => 'MFA vérifié avec succès']);
    }

    public function resetMFA($id)
    {
        $user = Users::findOrFail($id);
        $user->mfaSecret()?->delete();
        $user->authentification_2FA = false;
        $user->save();

        return response()->json(['message' => 'MFA réinitialisé']);
    }
}
