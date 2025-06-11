<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required|string|max:255',
            'conditions' => 'required|boolean',
            'newsletter' => 'required|boolean',
            'notifications' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = Users::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'authentification_2FA' => false,
        ]);

        Auth::login($user);

        Client::create([
            'nom' => $request->first_name,
            'prenom' => $request->last_name,
            'adresse' => $request->address,
            'telephone' => $request->phone,
            'id_utilisateur' => $user->id,
            'conditions' => $request->conditions,
            'newsletter' => $request->newsletter,
            'notifications' => $request->notifications,
        ]);

        return response()->json(['redirect' => '/mfa-setup']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        $user = Users::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }
    
        Auth::login($user);
        $request->session()->regenerate();
    
        if ($user->mfaSecret && $user->mfaSecret->is_verified) {
            return response()->json(['redirect' => '/mfa']);
        }
    
        return response()->json(['redirect' => '/dashboard']);
    }
    
    

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return response()->json(['message' => 'Logged out']);
    }
    

    public function me(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['user' => null], 401);
        }
    
        $hasMFA = $user->mfaSecret && $user->mfaSecret->is_verified;
    
        $client = DB::table('clients')->where('id_utilisateur', $user->id)->first();
        $livreur = DB::table('livreurs')->where('id_utilisateur', $user->id)->first();
        $prestataire = DB::table('prestataires')->where('id_utilisateur', $user->id)->first();
        $commercant = DB::table('commercants')->where('id_utilisateur', $user->id)->first();
        $admin = DB::table('administrateurs')->where('id_utilisateur', $user->id)->first();
    
        return response()->json([
            'user' => $user,
            'client' => $client,
            'livreur' => $livreur,
            'prestataire' => $prestataire,
            'commercant' => $commercant,
            'user_achievements' => $user->achievements()->get(),
            'admin' => $admin,
            'is_livreur' => !is_null($livreur),
            'is_prestataire' => !is_null($prestataire),
            'is_commercant' => !is_null($commercant),
            'is_admin' => !is_null($admin),
            'mfa_verified' => $hasMFA,
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $user = Auth::user();

        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
        $path = $request->file('photo')->storeAs('photos', $filename, 'public');

        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $user->photo_profil = $path;
        $user->save();

        return response()->json(['photo_url' => asset('storage/' . $path)]);
    }

    public function loginMobile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Users::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

}
