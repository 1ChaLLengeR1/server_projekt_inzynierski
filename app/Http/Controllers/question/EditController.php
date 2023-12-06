<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Storage;
use Throwable;
use App\Http\Controllers\HelperFunctions\Functions;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Type_Quiz;
use Tymon\JWTAuth\Facades\JWTAuth;

class EditController extends Controller
{
    public function EditQuestion(Request $request, Question $question, Answer $answer, Type_Quiz $type_guiz)
    {
        try {

            $question_id = $request->input('question_id');
            $quiz_id = $request->input('quiz_id');
            $type_id = $request->input('type_id');
            $user_id = $request->input('user_id');
            $text = $request->input('text');
            $image = $request->file('image');
            $array_delete_answers = $request->input('delete_answers');

            $comparison = new Functions();
            $formatBytes = new Functions();


            $array_validator = [
                "question_id" => "required|uuid|exists:question_table,id",
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "type_id" => "required|uuid|exists:type_table,id",
                "user_id" => "required",
                "text" => "required|min:10",
            ];
            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                "question_id.uuid" => "question_id musi być poprawnie zapisane!",
                "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                'question_id.exists' => 'Brak takie id question!',
                'quiz_id.exists' => 'Brak takie id quizu!',
                'type_id.exists' => 'Brak takie id typu!',
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
            ];

            if ($image) {
                $array_validator = [
                    "question_id" => "required|uuid|exists:question_table,id",
                    "quiz_id" => "required|uuid|exists:quiz_table,id",
                    "type_id" => "required|uuid|exists:type_table,id",
                    "user_id" => "required",
                    "text" => "required|min:10",
                    "image" => "mimes:jpeg,png,jpg|between:0,5120"
                ];

                $array_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    "question_id.uuid" => "question_id musi być poprawnie zapisane!",
                    "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                    "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                    'question_id.exists' => 'Brak takie id question!',
                    'quiz_id.exists' => 'Brak takie id quizu!',
                    'type_id.exists' => 'Brak takie id typu!',
                    'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                    'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $image->getClientOriginalExtension(),
                    'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($image->getSize()) . ', a musi ważyć od 0 do 5M!'
                ];
            }

            $validator = Validator::make($request->all(), $array_validator,  $array_message);

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
            error_log(count($array_delete_answers));
            // if (count($array_delete_answers) > 0) {
            //     foreach ($array_delete_answers as $key => $item) {

            //         $array_delete_answer = [
            //             "delete_answers.{$key}" => "required|uuid|exists:answer_table,id"
            //         ];
            //         $array_message_answers = [
            //             "required" => "Pole :attribute nie może być puste!",
            //             "uuid" => "{$item} to id jest źle zapisane!",
            //             "exists" => "{$item} brak takiego id w bazie odpowiedzi!"
            //         ];

            //         $validator_delete_answer = Validator::make($request->all(), $array_delete_answer, $array_message_answers);

            //         if ($validator_delete_answer->stopOnFirstFailure()->fails()) {
            //             return response()->json([
            //                 "status_code" => 401,
            //                 'status' => 'error',
            //                 'message' => $validator_delete_answer->errors()->first()
            //             ], 401);
            //         }

            //         $answer_single = $answer::where('id', $item)->first();
            //         if (Storage::exists($answer_single->path)) {
            //             Storage::delete($answer_single->path);
            //             $answer::where('id', $item)->delete();
            //         }
            //         // error_log(print_r($answer_single, true));
            //     }
            // }




            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "Poprawnie z modyfikowano pytanie",
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji modyfikacji pytania!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
