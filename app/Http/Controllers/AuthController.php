<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        // Try to find user in Employee table first, then User table
        $user = Employee::where('email', $request->email)->first();
        $userType = 'employee';
        
        if (!$user) {
            $user = User::where('email', $request->email)->first();
            $userType = 'user';
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create simple token and store in session
        $token = bin2hex(random_bytes(32));
        
        // Store authentication in session
        session([
            'auth_token' => $token,
            'auth_user_id' => $user->id,
            'auth_user_type' => $userType,
            'auth_user' => $user
        ]);

        $responseData = [
            'message' => 'Login successful',
            'user' => $user->load('roles'),
            'user_type' => $userType,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addHours(8)->toDateTimeString(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ];

        return response()->json($responseData);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'department' => $request->department,
            'phone' => $request->phone,
            'availability_for_sharing' => false,
        ]);

        // Assign default role
        $employee->assignRole('Employee');

        $tokenResult = $employee->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addHours(8);
        $token->save();

        $accessToken = $tokenResult->accessToken;

        $cookie = cookie(
            'access_token',
            $accessToken,
            480, // 8 hours
            '/',
            null,
            true,
            true,
            false,
            'strict'
        );

        return response()->json([
            'message' => 'Registration successful',
            'employee' => $employee->load('roles'),
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString(),
            'permissions' => $employee->getAllPermissions()->pluck('name')
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        session()->forget(['auth_token', 'auth_user_id', 'auth_user_type', 'auth_user']);

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        $userId = session('auth_user_id');
        $userType = session('auth_user_type');
        
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        // Fetch user based on type
        if ($userType === 'employee') {
            $user = Employee::with(['roles', 'experiences', 'projects', 'skills', 'achievements', 'certifications', 'education', 'knowledgeSharing'])->find($userId);
        } else {
            $user = User::with('roles')->find($userId);
        }
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        return response()->json([
            'user' => $user,
            'user_type' => $userType,
            'permissions' => $user->getAllPermissions()->pluck('name')
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Revoke current token
        $user->token()->revoke();
        
        // Create new token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addHours(8);
        $token->save();

        $accessToken = $tokenResult->accessToken;

        $cookie = cookie(
            'access_token',
            $accessToken,
            480, // 8 hours
            '/',
            null,
            true,
            true,
            false,
            'strict'
        );

        return response()->json([
            'message' => 'Token refreshed successfully',
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString()
        ])->withCookie($cookie);
    }

    public function updateProfile(Request $request)
    {
        $employee = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:employees,email,' . $employee->id,
            'position' => 'sometimes|required|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'internal_extension' => 'nullable|string|max:10',
            'profile_picture' => 'nullable|string|max:500',
            'linkedin' => 'nullable|url|max:500',
            'facebook' => 'nullable|url|max:500',
            'twitter' => 'nullable|url|max:500',
            'github' => 'nullable|url|max:500',
            'personal_website' => 'nullable|url|max:500',
            'languages' => 'nullable|string|max:500',
            'mentoring_interest' => 'nullable|string',
            'availability_for_sharing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee->update($request->only([
            'first_name', 'last_name', 'email', 'position', 'department',
            'phone', 'internal_extension', 'profile_picture', 'linkedin',
            'facebook', 'twitter', 'github', 'personal_website', 'languages',
            'mentoring_interest', 'availability_for_sharing'
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'employee' => $employee->fresh()->load('roles')
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee = $request->user();

        if (!Hash::check($request->current_password, $employee->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => ['current_password' => ['Current password is incorrect']]
            ], 422);
        }

        $employee->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email'])
            : response()->json(['message' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Failed to reset password'], 400);
    }
}
