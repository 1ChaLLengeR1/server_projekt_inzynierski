<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperFunctions\Functions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControler extends Controller
{

    public function register(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                "username" => "required|min:6|max:12",
                "email" => "required|email|unique:users_table,email",
                "password" => "required|min:8",
            ], [
                'required' => 'Pole :attribute nie może być puste!',
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                'max' => 'Pole :attribute może mieć maksylamnie :max znaków!',
                'email' => 'Pole :attribute musi zawierać prawidłowy adres email!',
                'unique' => "Taki adres email {$request->input('email')} jest już wykorzystany!"
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {

                if($validator->errors()->first('email')){
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors()->first()
                    ], Response::HTTP_NOT_ACCEPTABLE);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = new User();

            $user->id = Uuid::uuid4()->toString();
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'), [
                'rounds' => 10,
            ]);
            $user->type_user = "user";
            $user->save();

            return response()->json([
                "status" => "success",
                "message" => "Poprawnie stworzono użytkownika!"
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                "status" => "error",
                "message" => "Błąd w skecji tworzenia użytkownika!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|email|exists:users_table,email",
                "password" => "required",
                "remember_me" => "required"
            ], [
                'required' => 'Pole :attribute nie może być puste!',
                'email' => 'Pole :attribute musi zawierać prawidłowy adres email!',
                'exists' => 'Brak takie adresu email!'
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ], Response::HTTP_UNAUTHORIZED);
            }
            $hashPassword = User::where('email', $request->input('email'))->value('password');
            if (!Hash::check($request->input('password'), $hashPassword)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nieprawidłowe dane logowania!'
                ], Response::HTTP_UNAUTHORIZED);
            }


            $token = JWTAuth::attempt([
                "email" => $request->input("email"),
                "password" => $request->input("password")
            ]);

            $user = User::where('email', $request->input('email'))->first();

            # wiktor szef

            return response()->json([
                "status" => "success",
                "message" => "Poprawnie zostałeś zalogowany!",
                "user" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "email" => $user['email'],
                    "type_user" => $user['type_user'],
                    "remember_me" => $request->input('remember_me')
                ],
                "token" => [
                    "access_token" => $token,
                    "token_expire" => Carbon::now()->timezone('Europe/Warsaw')->addMinute(env('JWT_TTL'))
                ]
            ], Response::HTTP_OK);


        } catch (Throwable $e) {
            return response()->json([
                "status" => "error",
                "message" => "Błąd w sekcji logowania użytkownika!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function refreshToken()
    {
        try {


            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            $checkFunctions = new Functions;
            $paramsDataBase = new User;
            if ($checkFunctions->checkIdParam($apy['sub'], $paramsDataBase) !== true) {
                return $checkFunctions->checkIdParam($apy['sub'], $paramsDataBase);
            }


            $newToken = auth()->refresh();
            return response()->json([
                'status' => 'success',
                'message' => 'Poprawnie token został przeładowany!',
                "access_token" => $newToken,
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Błąd w sekcji refreshToken!',
                'server_message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout()
    {
        try {

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            $checkFunctions = new Functions;
            $paramsDataBase = new User;
            if ($checkFunctions->checkIdParam($apy['sub'], $paramsDataBase) !== true) {
                return $checkFunctions->checkIdParam($apy['sub'], $paramsDataBase);
            }

            auth()->logout();
            return response()->json([
                'status' => 'success',
                "message" => "Wylogowałes się pomyślnie!"
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Błąd w sekcji wylogowania tokenu!',
                'server_message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
