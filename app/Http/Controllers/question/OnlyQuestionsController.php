<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\HelperFunctions\Functions;

class OnlyQuestionsController extends Controller
{
    public function OnlyQuestions(Request $request, Question $question)
    {
        try {
            $quiz_id = $request->input('quiz_id');
            $user_id = $request->input('user_id');
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "user_id" => "required|uuid|exists:users_table,id"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "Id w :attribute jest źle zapisane!",
                "exists" => "Id w :attribute nie istnieje w bazie!"
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
                    'message' => "Nie poprawne parametry Id!"
                ], 401);
            }

            $question = $question::where('quiz_id', $quiz_id)->where('user_id', $user_id)->get();

            return response()->json($question, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji pobierania samych questions bez answers!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
