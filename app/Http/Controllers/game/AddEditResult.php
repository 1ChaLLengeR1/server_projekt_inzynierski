<?php

namespace App\Http\Controllers\game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use App\Models\GameResult;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\HelperFunctions\Functions;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AddEditResult extends Controller
{
    public function AddEdit(Request $request, User $user, GameResult $game_result)
    {
        try {

            $user_id = $request->input('user_id');
            $quiz_id = $request->input('quiz_id');
            $result = $request->input('result');
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "user_id" => "required|uuid|exists:users_table,id",
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "result" => "required|numeric"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "Id w :attribute jest źle zapisane!",
                "exists" => "Id w :attribute nie istnieje w bazie!",
                "numeric" => "Rezultat musi być liczbą!"
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }


            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry id tokenu i użytkownika!"
                ], 401);
            }


            $game_result = $game_result::where("user_id", $user_id)->first();
            if ($game_result === null) {

                $user = $user::where('id', $user_id)->first();
                $game_result = new GameResult();

                $game_result->id = Uuid::uuid4()->toString();
                $game_result->quiz_id = $quiz_id;
                $game_result->name = $user->username;
                $game_result->result = $result;
                $game_result->user_id = $user_id;
                $game_result->save();

                return response()->json([
                    "status_code" => 201,
                    "status" => "success",
                    "message" => "Dodano nowy wynik do tabeli!",
                ], 201);
            } else {
                if ($game_result->result < $result) {
                    $game_result->result = $result;
                    $game_result->save();
                    return response()->json([
                        "status_code" => 200,
                        "status" => "success",
                        "message" => "Wynik został poprawiony!",
                    ], 200);
                } else {
                    return response()->json([
                        "status_code" => 200,
                        "status" => "success",
                        "message" => "Twój najlepszy wynik to {$game_result->result}, a obecny to {$result}."
                    ], 200);
                }
            }
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji dodanie/edycji wyniku użytkownika!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
