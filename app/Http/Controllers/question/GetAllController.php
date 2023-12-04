<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Type_Quiz;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GetAllController extends Controller
{
    public function GetAllQuestions(Request $request, Question $question, Answer $answers, Type_Quiz $type_quiz)
    {
        try {

            $type_question = $request->input('type');
            $validator = Validator::make($request->all(), [
                "type" => 'required|exists:type_table,type'
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'exists' => 'Brak takiego typu pytania!',
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $main_array = [];
            $result = DB::table('question_table')
                ->join('type_table', 'question_table.type_id', '=', 'type_table.id')
                ->select('question_table.id', 'user_id', 'quiz_id', 'type_id', 'text', 'path', 'link_image')
                ->where('type_table.type', '=', $type_question)
                ->get();


            foreach ($result as $key => $item) {

                $type = $type_quiz::where('id', $item->type_id)->first();
                $answer = $answers::where('question_id', $item->id)->get();

                $main_array[] = [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'quiz_id' => $item->quiz_id,
                    'type_quiz' => [
                        'id' => $type['id'],
                        "name" => $type['name'],
                        'type' => $type['type']
                    ],
                    'text' => $item->text,
                    'answer_array' => $answer,
                    'path' => $item->path,
                    'link_image' => $item->link_image
                ];
            }

            return response()->json($main_array, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania wszystkich pytań!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
