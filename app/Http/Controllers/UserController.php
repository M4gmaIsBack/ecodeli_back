<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'required|min:6',
            'authentification_2FA' => 'boolean',

        ]);
            $users = Users::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'authentification_2FA' => $request->authentification_2FA,
            ]);

            return response()->json($users, 201);
        }
    public function index()
    {
        return response()->json(Users::all());
    }

    public function update(Request $request, $id)
    {
        $user = Users::findOrFail($id);
    
        $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6', 
            'authentification_2FA' => 'boolean',
        ]);
    
        if ($request->has('password') && !empty($request->password)) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            $request->offsetUnset('password');
        }
    
        $user->update($request->all());
    
        return response()->json($user, 200);
    }
    
    
    public function destroy($id)
    {
        $users = Users::findOrFail($id);
        $users->delete();

        return response()->json(['message' => "Users {$id} supprimé avec succès"], 200);
    }
}
