<?php

namespace App\Http\Controllers;

use App\Events\UserLoginUsingPhoneNumberEvent;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Resources\LoginResource;
use App\Models\TempPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function userLogin(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt([
            'phone_number' => $request->phone_number,
            'password' => $request->password
        ])) {
            return response()->json(new LoginResource(Auth::user()));
        } else {
            return response()->json(['massage' => __('auth.failed')], 422);
        }
    }

    public function checkPhoneNumber(SendCodeRequest $request): JsonResponse
    {
        if (User::where('phone_number', $request->phone_number)->exists()) {
            $user_exist = true;
        } else {
            $user_exist = false;
            if ($this->doesPhoneNumberHasUnexpiredCodes($request->input('phone_number')))
                event(new UserLoginUsingPhoneNumberEvent($request->phone_number));
        }
        return response()->json(['phone_number' => $request->phone_number, 'user_exits' => $user_exist]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            if ($this->checkTempPassword($request->phone_number, $request->code)) {
                $user = User::create([
                    'phone_number' => $request->input('phone_number'),
                    'password' => $request->input('password'),
                    'name' => $request->input('name'),
                ]);
//                $user->assignRole('user');
                return response()->json(new LoginResource($user));
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['massage' => __('auth.failed')], 422);
        } catch (\ErrorException $e) {
            return response()->json(['massage' => "{$e->getMessage()}"], 422);
        }
        return response()->json(['massage' => __('auth.failed')], 422);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }


    protected function checkTempPassword($phone_number, $code, bool $deleteCode = true): bool
    {
        $tempPass = TempPassword::where('phone_number', $phone_number)
            ->where('code', $code)->orderByDesc('expire_at')->firstOrFail();
        if ($tempPass->expire_at < Carbon::now())
            throw new \ErrorException(__('auth.code_expired'));
        if ($deleteCode)
            $tempPass->delete();
        return true;
    }

    protected function doesPhoneNumberHasUnexpiredCodes($phone_number): bool
    {
        return TempPassword::where('phone_number', $phone_number)
                ->where('expire_at', '<=', now())
                ->count() < 3;
    }


}
