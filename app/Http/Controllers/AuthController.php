<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'contact' => 'required',
            'matric' => 'required|unique:users',
            'user_type' => 'required|in:Buyer,Seller'
        ]);

        $otp = rand(100000, 999999);
        $otpExpiry = Carbon::now()->addMinutes(10);

        \Log::info("Registering user: " . $request->email);
        \Log::info("Generated OTP: " . $otp . " | Expiry: " . $otpExpiry);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->contact = $request->contact;
        $user->matric = $request->matric;
        $user->user_type = $request->user_type;
        $user->otp = $otp;
        $user->otp_expiry = $otpExpiry;
        $user->is_verified = 0;
        $user->save();
        \Log::info("User saved successfully: " . json_encode($user));

       try {
        Mail::to($user->email)->send(new OtpMail($otp));
       } catch (\Exception $e) {
            \Log::error("OTP email sending failed: " . $e->getMessage());
            return response()->json(['message' => 'Registration successful, but OTP email failed to send.', 'error' => $e->getMessage()], 500);
       }

        return response()->json([
            'message' => 'Registration successful! Check your email for OTP verification.',
            'user' => $user
       ], 201);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = rand(100000, 999999);
        $expiry = Carbon::now()->addMinutes(10);

        $user = User::where('email', $request->email)->first();

        \Log::info("User requested OTP resend: " . $request->email);

        if ($user->otp_expiry && Carbon::now()->lt($user->otp_expiry)) {
            \Log::info("OTP still valid, not resending: " . $user->otp);
            return response()->json(['message' => 'OTP is still valid. Please check your email.'], 400);
        }


        \Log::info("Generated OTP: $otp for email: " . $request->email);

        // Store OTP in database
        $update = User::where('email', $request->email)->update([
            'otp' => $otp,
            'otp_expiry' => $expiry,
        ]);

        if (!$update) {
            \Log::error("Failed to update OTP for email: " . $request->email);
            return response()->json(['error' => 'Failed to store OTP in database.'], 500);
        }

        $updatedUser = User::where('email', $request->email)->first();
        \Log::info("Updated OTP in DB: " . $updatedUser->otp);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'OTP sent successfully.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        \Log::info("Verifying OTP for: " . $request->email);
        \Log::info("Stored OTP: " . $user->otp . " | Entered OTP: " . $request->otp);
        \Log::info("Stored Expiry: " . $user->otp_expiry);

        // Check OTP match
        if (!$user->otp || (string)$request->otp !== (string)$user->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 401);
        }

        // Check OTP expiration
        if (!$user->otp_expiry || Carbon::now()->gt($user->otp_expiry)) {
            return response()->json(['error' => 'OTP expired.'], 401);
        }

        // OTP is valid -> Verify user
        $user->update([
            'is_verified' => 1,
            'otp' => null,
            'otp_expiry' => null
        ]);

        \Log::info("OTP verified successfully for: " . $request->email);

        return response()->json([
        'message' => 'OTP verified successfully! You can now log in.',
        'status' => true
        ], 200);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'details' => $e->getMessage()], 500);
        }
    }



    public function logout(Request $request)
    {
        \Log::info("Logout request received.");

        $token = $request->bearerToken();
        \Log::info("Received Token: " . ($token ?? "No Token Provided"));

        $user = Auth::user();
    
        if ($user) {
            \Log::info("User authenticated: " . $user->email);
            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Logout successful'
            ]);
        }

        \Log::warning("User not authenticated during logout attempt.");
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated'
        ], 401);
    }

}
