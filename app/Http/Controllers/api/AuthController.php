<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;
use Helper;
use DB;
class AuthController extends Controller
{
    
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $otpCode = rand(1000, 9999);

        // Remove old OTPs for this number
        Otp::where('mobile', $request->mobile)->delete();

        // Store new OTP (expires in 5 min)
        Otp::create([
            'mobile' => $request->mobile,
            'otp' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            $helper = new Helper();
            $message = "Your verification OTP is *{$otpCode}*. Valid for 5 minutes.";
            $helper->sendWhatsApp($request->mobile, $message);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otpCode // ⚠️ Remove in production
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4',
            'type' => 'required|in:login,register', // 👈 client must tell the purpose
            'name' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // ✅ Verify OTP
        $otpRecord = Otp::where('mobile', $request->mobile)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();
    
        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $user = User::where('user_name', $request->mobile)->first();
    
            // 🟢 Handle login
            if ($request->type === 'login') {
                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not found. Please register first.',
                    ], 404);
                }
    
                $message = 'Login successful';
            }
    
            // 🟢 Handle registration
            elseif ($request->type === 'register') {
                if ($user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User already exists. Please login instead.',
                    ], 409);
                }
    
                $user = User::create([
                    'user_name' => $request->mobile,
                    'mobile' => $request->mobile,
                    'full_name' => $request->name ?? 'User_' . Str::random(5),
                    'password' => Hash::make('otp_login'),
                    'role_id' => 2,
                ]);
    
                $message = 'Registration successful';
            }
    
            // ✅ OTP consumed → delete it
            $otpRecord->delete();
            DB::commit();
    
            // Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => $message,
                'type' => $request->type,
                'user' => $user,
                'token' => $token,
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    
}