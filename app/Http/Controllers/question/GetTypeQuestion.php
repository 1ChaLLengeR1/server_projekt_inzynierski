<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;


class GetTypeQuestion extends Controller
{
    public function GetTypeQuestion(Request $request)
    {
        try {
            $id_question = $request->input('id');

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
