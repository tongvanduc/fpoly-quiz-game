<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthenController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $user = User::query()->where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return \response()->json([
                    'error' => trans('auth.failed')
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = $user->createToken(__CLASS__);

            return \response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error('Exception', [$exception]);

            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'machine_id' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            /** @var User $user */
            $user = User::create([
                'uuid' => $request->machine_id . '|' . time(),
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_user' => TYPE_USER_STUDENT,
            ]);

            event(new Registered($user));

            $token = $user->createToken(__CLASS__);

            return \response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error('Exception', [$exception]);

            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            /** @var User $user */
            $user = \request()->user();

            // Xóa thằng phiên đăng nhập hiện tại
            $user->currentAccessToken()->delete();

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error('Exception', [$exception]);

            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
