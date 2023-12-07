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
            $array_answers_image = $request->file('array_answers_image');

            if (is_null($text) && is_null($image)) {
                return response()->json([
                    "status_code" => 400,
                    "status" => "error",
                    "message" => "pole text i image nie mogą być puste!",
                ], 400);
            }

            $array_validators = [
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "type_id" => "required|uuid|exists:type_table,id",
                "user_id" => "required",
            ];

            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                'quiz_id.exists' => 'Brak takie id quizu!',
                'type_id.exists' => 'Brak takie id typu!',
            ];

            if ($image) {

                $array_validators = [
                    "quiz_id" => "required|uuid|exists:quiz_table,id",
                    "type_id" => "required|uuid|exists:type_table,id",
                    "user_id" => "required",
                    "image" => "mimes:jpeg,png,jpg|between:0,5120"
                ];

                $array_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                    "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                    'quiz_id.exists' => 'Brak takie id quizu!',
                    'type_id.exists' => 'Brak takie id typu!',
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
                "array_answers.*.index" => "required",
                "array_answers.*.answer_type" => "required|boolean",
            ];

            $array_answere_message = [
                "required" => "Pole :attribute nie może być puste!",
                "boolean" => "Pole :attribute musi być wartością Boolean!"
            ];

            $validator_answere = Validator::make($request->all(), $array_answere_validator, $array_answere_message);
            if ($validator_answere->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => $validator_answere->errors()->first()
                ], 401);
            }



            if ($array_answers_image) {
                foreach ($array_answers_image as $key => $item) {
                    $array_answere_image_validator = [
                        "array_answers_image.{$key}" => "mimes:jpeg,png,jpg|between:0,5120"
                    ];

                    $array_answere_image_message = [
                        'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $item->getClientOriginalExtension(),
                        'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($item->getSize()) . ', a musi ważyć od 0 do 5M!'
                    ];

                    $validator_answere_images = Validator::make($request->all(), $array_answere_image_validator, $array_answere_image_message);

                    if ($validator_answere_images->stopOnFirstFailure()->fails()) {
                        return response()->json([
                            "status_code" => 402,
                            'status' => 'error',
                            'message' => $validator_answere_images->errors()->first()
                        ], 402);
                    }
                }
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 403,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry id tokenu i użytkownika!"
                ], 403);
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

            $decode = json_decode($array_answers);

            foreach ($decode  as $key => $item) {

                $new_id =  Uuid::uuid4()->toString();
                $answer = new Answer();
                $answer->id = $new_id;
                $answer->user_id = $user_id;
                $answer->question_id = $id;
                $answer->text = $item->text;
                $answer->answer_type = $item->answer_type;
                $answer->path = '';
                $answer->link_image = '';


                if (isset($array_answers_image)) {
                    foreach ($array_answers_image as $key => $image) {

                        $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $exp = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);

                        if ($item->index ===  $filename) {

                            $new_name_file = time() . '_' . $new_id . '.' . $exp;

                            $filePath = $image->storeAs('/public/files/answer_images/', $new_name_file);

                            $link_image = asset('/storage/files/answer_images/' . $new_name_file);

                            $answer->path = $filePath;
                            $answer->link_image = $link_image;
                            $answer->save();
                        }
                    }
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
