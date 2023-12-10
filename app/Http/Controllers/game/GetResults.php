<?php

namespace App\Http\Controllers\game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameResult;
use Throwable;
use Illuminate\Support\Facades\Validator;

class GetResults extends Controller
{
    public function GetResult(Request $request, GameResult $result)
    {
        try {
            $limit = $request->input('limit');
            $quiz_id = $request->input('quiz_id');

            $validator = Validator::make($request->all(), [
                "quiz_id" => "required|uuid|exists:quiz_table,id",
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "Id jest źle zapisane!",
                "exists" => "Id nie istnieje w bazie!",
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }


            if ($limit === null) {
                $result = $result::where('quiz_id', $quiz_id)->orderBy('result', 'asc')->get();
            } else {
                $result = $result::where('quiz_id', $quiz_id)->orderBy('result', 'asc')->limit($limit)->get();
            }

            return response()->json($result, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji pobierania wyników!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
