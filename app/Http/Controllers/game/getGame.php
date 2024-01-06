<?php

namespace App\Http\Controllers\game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use App\Http\Controllers\HelperFunctions\Functions;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Type_Quiz;
use App\Models\Quiz;

use function PHPSTORM_META\type;

class getGame extends Controller
{
    public function getGame(Request $request, Quiz $quiz, Question $question, Answer $answer, Type_Quiz $type_quiz)
    {
        try {
            $quiz_id = $request->input('quiz_id');

            $validator = Validator::make($request->all(), [
                "quiz_id" => "required|uuid|exists:quiz_table,id"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "Id musi być poprawnie zapisane!",
                "exists" => "Brak takiego id quizu w bazie!"
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $game = [];
            $quiz = $quiz::where('id', $quiz_id)->first();
            $get_all_questions = $question::where('quiz_id', $quiz_id)->get();


            $max_index = $quiz->quantity;
            $question = [];
            $answers = [];

            foreach ($get_all_questions->shuffle() as $key => $item) {
                if ($max_index > 0) {
                    if ($key === $max_index) {
                        break;
                    }
                }

                $get_all_answers = $answer::where('question_id', $item['id'])->get();

                $get_type_question = $type_quiz::where('id', $item['type_id'])->first();

                foreach ($get_all_answers->shuffle() as $key => $item_answer) {
                    $answers[] = (object)[
                        "id" => $item_answer['id'],
                        "question_id" => $item_answer['question_id'],
                        "text" => $item_answer['text'],
                        "link_image" => $item_answer['link_image'],
                        "answer_type" => $item_answer['answer_type']
                    ];
                }

                $question[] = (object)[
                    "id" => $item['id'],
                    "quiz_id" => $item['quiz_id'],
                    "text" => $item['text'],
                    "link_image" => $item['link_image'],
                    "type" => $get_type_question,
                    "answers" => $answers
                ];

                $answers = [];
            }


            $game[] = (object)[
                "id" => $quiz->id,
                "name" => $quiz->name,
                "description" => $quiz->description,
                "link_image" => $quiz->link_image,
                "questions" =>  $question
            ];

            return response()->json($game[0], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji pobierania gry!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
