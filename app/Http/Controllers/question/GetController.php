<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Type_Quiz;

class GetController extends Controller
{
    public function GeqSingleQuestion(Request $request, Question $question, Answer $answers, Type_Quiz $type_quiz)
    {
        try {
            $id = $request->input('id');

            $validator = Validator::make($request->all(), [
                'id' => 'required|uuid|exists:question_table,id',
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'exists' => 'Brak takiego id pytania!',
                'uuid' => 'Błąd w składni id!'
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $single_question = [];

            $question = $question::where('id', $id)->first();
            $answer = $answers::where('question_id', $id)->get();
            $type = $type_quiz::where('id', $question['type_id'])->first();

            $single_question[] = (object)[
                "id" => $question['id'],
                "user_id" => $question['user_id'],
                "quiz_id" => $question['quiz_id'],
                "type_question" => [
                    'id' => $type['id'],
                    'name' => $type['name'],
                    'type' => $type['type']
                ],
                "text" => $question['text'],
                "array_answer" => $answer,
                "path" => $question['path'],
                "link_image" => $question['link_image']
            ];

            return response()->json($single_question[0], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania pojedynczego pytania!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
