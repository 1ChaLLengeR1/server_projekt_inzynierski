<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class GetTypeController extends Controller
{
    public function GetTypeQuestion(Request $request)
    {
        try {
            $id_question = $request->input('id');

            $validator = Validator::make($request->all(), [
                "id" => "required|uuid|exists:question_table,id"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "Uuid jest źle zapisane!",
                "exists" => "Brak takiego id w bazie question!",
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }


            $result = DB::table('type_table')
                ->select('type_table.id', 'name', 'description', 'type')
                ->join('question_table', 'question_table.type_id', '=', 'type_table.id')
                ->where('question_table.id', '=', "{$id_question}")
                ->get();

            return response()->json($result[0], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji pobierania typu question!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
