<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Exception;

class NewPasswordController extends Controller
{
    public function linkReset($token)
    {
        return view('customauth.password.reset', compact('token'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return ResponseFormatter::success(__($status), 'Forgot Password Berhasil');
        }
        return ResponseFormatter::error([trans($status)], 'Forgot Password Gagal', 404);
    }

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return ResponseFormatter::error(null, 'Your current password does not matches with the password.');
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return ResponseFormatter::error(null, 'New Password cannot be same as your current password.');
        }

        try {
            $validate = $request->validate([
                'current-password' => 'required',
                'new-password' => 'required_with:new-password-confirm|same:new-password-confirm|min:8',
                'new-password-confirm' => 'min:8',
            ]);

            $user = Auth::user();
            $user->password = Hash::make($request->get('new-password'));
            $user->save();

            return ResponseFormatter::success(null, 'Change password success');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e,
            ], 'Change Password Failed', 500);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
