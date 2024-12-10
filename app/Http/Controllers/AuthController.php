<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetEmail;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        }

        $user = auth()->user();

        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Aucun utilisateur trouvé avec cet email.'], 404);
        }

        $token = Password::createToken($user);

        $resetLink = ('http://localhost:3000/password-reset?token=' . $token . '&email=' . urlencode($user->email));

        Mail::to($user->email)->send(new PasswordResetEmail($resetLink));

        return response()->json(['message' => 'Reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        // Validation des données
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Réinitialisation du mot de passe
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        // Retourner la réponse
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset.']);
        }

        // Gestion des erreurs
        switch ($status) {
            case Password::INVALID_TOKEN:
                return response()->json(['error' => 'This password reset token is invalid.'], 400);
            case Password::INVALID_USER:
                return response()->json(['error' => 'No user found with this email address.'], 404);
            default:
                return response()->json(['error' => 'Unable to reset password.'], 500);
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
    
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
    
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            $user->profile_picture = $path;
            $user->save(); 
        }
    
        return response()->json(['message' => 'Photo de profil mise à jour avec succès !', 'profile_picture' => $user->profile_picture], 200);
    }

}
