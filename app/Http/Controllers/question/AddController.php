<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Throwable;
use App\Http\Controllers\HelperFunctions\Functions;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Type_Quiz;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddController extends Controller
{
    public function AddQuestion(Request $request, Question $question, Answer $answer, Type_Quiz $type_guiz)
    {
        try {

            $comparison = new Functions();
            $formatBytes = new Functions();

            $id = Uuid::uuid4()->toString(); # creating at the server.
            $quiz_id = $request->input('quiz_id'); # body
            $type_id = $request->input('type_id'); # body
            $user_id = $request->input('user_id'); # body
            $text = $request->input('text'); # body
            $image = $request->file('image'); # body
            $array_answers = $request->input('array_answers'); # body
            $array_answers_file = $request->file('array_answers');

            $array_validators = [
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "type_id" => "required|uuid|exists:type_table,id",
                "user_id" => "required",
                "text" => "required|min:10",
            ];

            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                'quiz_id.exists' => 'Brak takie id quizu!',
                'type_id.exists' => 'Brak takie id typu!',
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
            ];

            if ($image) {

                $array_validators = [
                    "quiz_id" => "required|uuid|exists:quiz_table,id",
                    "type_id" => "required|uuid|exists:type_table,id",
                    "user_id" => "required",
                    "text" => "required|min:10",
                    "image" => "mimes:jpeg,png,jpg|between:0,5120"
                ];

                $array_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                    "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                    'quiz_id.exists' => 'Brak takie id quizu!',
                    'type_id.exists' => 'Brak takie id typu!',
                    'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                    'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $image->getClientOriginalExtension(),
                    'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($image->getSize()) . ', a musi ważyć od 0 do 5M!'
                ];
            }

            $validator = Validator::make($request->all(), $array_validators, $array_message);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }


            $array_answere_validator = [
                "array_answers.*.answer_type" => "required|boolean",
                "array_answers.*.text" => "required",
            ];

            $array_answere_message = [
                "required" => "Pole :attribute nie może być puste!",
                "boolean" => "Pole :attribute musi być wartością Boolean!"
            ];

            if ($array_answers_file) {
                foreach ($array_answers_file as $key => $item) {
                    $array_answere_validator = [
                        "array_answers.{$key}.answer_type" => "required|boolean",
                        "array_answers.{$key}.text" => "required",
                        "array_answers.{$key}.images" => "mimes:jpeg,png,jpg|between:0,5120"
                    ];

                    $array_answere_message = [
                        "required" => "Pole :attribute nie może być puste!",
                        "boolean" => "Pole :attribute musi być wartością Boolean!",
                        'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $item['images']->getClientOriginalExtension(),
                        'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($item['images']->getSize()) . ', a musi ważyć od 0 do 5M!'
                    ];

                    $validator_answere = Validator::make($request->all(), $array_answere_validator, $array_answere_message);

                    if ($validator_answere->stopOnFirstFailure()->fails()) {
                        return response()->json([
                            "status_code" => 400,
                            'status' => 'error',
                            'message' => $validator_answere->errors()->first()
                        ], 401);
                    }
                }
            }

            $validator_answere = Validator::make($request->all(), $array_answere_validator, $array_answere_message);

            if ($validator_answere->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator_answere->errors()->first()
                ], 401);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry id tokenu i użytkownika!"
                ], 402);
            }


            if ($image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $filePath = $image->storeAs('/public/files/question_images/', $fileName);
                $filePathServer = asset('/storage/files/question_images/' . $fileName);
                $question->path = $filePath;
                $question->link_image = $filePathServer;
            } else {
                $question->path = '';
                $question->link_image = '';
            }

            $question->id = $id;
            $question->user_id = $user_id;
            $question->quiz_id = $quiz_id;
            $question->type_id = $type_id;
            $question->text = $text;
            $question->save();

            foreach ($array_answers as $index => $answers) {
                $answer = new Answer();
                $answer->id = Uuid::uuid4()->toString();
                $answer->user_id = $user_id;
                $answer->question_id = $id;
                $answer->text = $answers['text'];
                $answer->answet_type = $answers['answer_type'];
                $answer->path = '';
                $answer->link_image = '';

                if (isset($array_answers_file[$index])) {
                    $fileName = time() . '_' . rand() . '_' . $array_answers_file[$index]['images']->getClientOriginalName();
                    $filePath = $array_answers_file[$index]['images']->storeAs('/public/files/answer_images/', $fileName);
                    $filePathServer = asset('/storage/files/answer_images/' . $fileName);

                    $answer->path = $filePath;
                    $answer->link_image = $filePathServer;
                }
                $answer->save();
            }

            return response()->json([
                "status_code" => 201,
                "status" => "success",
                "message" => "Dodano poprawnie pytania!",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji dodawania pytania!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
