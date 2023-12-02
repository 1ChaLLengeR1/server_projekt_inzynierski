<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Validator;

class GetAllController extends Controller
{
    public function GetAllQuestions(Question $question, Answer $answer)
    {
        try {
            // error_log(print_r($question::all(), true));
            // error_log(print_r($answer::all(), false));

            $main_array = [];

            foreach ($question::all() as $key => $item) {
                error_log($item['id']);
            }



            // $array[] = (object)['name' => 'artek', 'age' => 23];
            // $array[] = (object)['name' => 'sebastian', 'age' => 23];



            return response()->json([], 200);
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
