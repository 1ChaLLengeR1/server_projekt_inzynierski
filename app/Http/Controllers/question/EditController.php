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
            $array_delete_answers = $request->input('delete_answers');
            $array_answers = json_decode($request->input('array_answers'));
            $array_answers_edit = json_decode($request->input("array_answers_edit"), true);
            $array_images = $request->file('array_images');
            $image = $request->file('image');

            $comparison = new Functions();
            $formatBytes = new Functions();


            if (is_null($text) && is_null($image)) {
                return response()->json([
                    "status_code" => 400,
                    "status" => "error",
                    "message" => "pole text i image nie mogą być puste!",
                ], 400);
            }


            $array_validator = [
                "question_id" => "required|uuid|exists:question_table,id",
                "quiz_id" => "required|uuid|exists:quiz_table,id",
                "type_id" => "required|uuid|exists:type_table,id",
                "user_id" => "required",
            ];
            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                "question_id.uuid" => "question_id musi być poprawnie zapisane!",
                "quiz_id.uuid" => "quiz_id musi być poprawnie zapisane!",
                "type_id.uuid" => "type_id musi być poprawnie zapisane!",
                'question_id.exists' => 'Brak takie id question!',
                'quiz_id.exists' => 'Brak takie id quizu!',
                'type_id.exists' => 'Brak takie id typu!',
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $array_validator = [
                    "question_id" => "required|uuid|exists:question_table,id",
                    "quiz_id" => "required|uuid|exists:quiz_table,id",
                    "type_id" => "required|uuid|exists:type_table,id",
                    "user_id" => "required",
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
            // Checking by validator array delete answer
            if ($array_delete_answers) {
                foreach ($array_delete_answers as $key => $item) {
                    if (!empty($item)) {
                        $array_delete_answer = [
                            "delete_answers.{$key}" => "uuid|exists:answer_table,id"
                        ];
                        $array_message_answers = [
                            "uuid" => "id jest źle zapisane w tablicy rzeczy do usunięcia!",
                            "exists" => "brak takiego id w bazie odpowiedzi w tablicy rzeczy do usunięcia!"
                        ];

                        $validator_delete_answer = Validator::make($request->all(), $array_delete_answer, $array_message_answers);

                        if ($validator_delete_answer->stopOnFirstFailure()->fails()) {
                            return response()->json([
                                "status_code" => 402,
                                'status' => 'error',
                                'message' => $validator_delete_answer->errors()->first()
                            ], 402);
                        }
                    }
                }
            }

            // Checking by validator array images
            if ($array_images) {
                foreach ($array_images as $key => $image) {
                    $array_images_validator = [
                        "array_images.{$key}" => "mimes:jpeg,png,jpg|between:0,5120"
                    ];

                    $array_images_mesage = [
                        'mimes' => 'tablica zdjęć -Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $image->getClientOriginalExtension(),
                        'between' => 'tablica zdjęć - Zdjęcie waży: ' . $formatBytes->formatBytes($image->getSize()) . ', a musi ważyć od 0 do 5M!'
                    ];

                    $validator_array_images = Validator::make($request->all(), $array_images_validator, $array_images_mesage);

                    if ($validator_array_images->stopOnFirstFailure()->fails()) {
                        return response()->json([
                            "status_code" => 403,
                            'status' => 'error',
                            'message' => $validator_array_images->errors()->first()
                        ], 403);
                    }
                }
            }

            // Validator array answers
            if ($array_answers) {
                foreach ($array_answers as $key => $item) {
                    $error_information = '';
                    if (empty($item->index) === '') {
                        $error_information = "Index nie może być pusty w obikecie {$key}";
                    }

                    if ($item->answer_type !== 1 && $item->answer_type !== 0) {
                        $error_information = "answer_type nie jest boolean w obikecie {$key}";
                    }


                    if (!empty($error_information)) {
                        return response()->json([
                            "status_code" => 404,
                            'status' => 'error',
                            'message' => $error_information
                        ], 404);
                    }

                    $error_information = '';
                }
            }

            // Checking by Validator array_answers_edit
            if ($array_answers_edit) {
                $valid_array = [
                    "*.index" => "required|uuid|exists:answer_table,id",
                    "*.answer_type" => "required|boolean",
                    "*.delete_image" => "required|boolean",
                ];
                $valid_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    "uuid" => "id jest źle zapisane w tablicy rzeczy do usunięcia!",
                    "exists" => "brak takiego id w bazie odpowiedzi w tablicy rzeczy do usunięcia!",
                    "boolean" => "wartość :attribute jest zapisany nie jako boolean!",
                ];


                $validator_edit = Validator::make($array_answers_edit, $valid_array,  $valid_message);

                if ($validator_edit->stopOnFirstFailure()->fails()) {
                    return response()->json([
                        "status_code" => 405,
                        'status' => 'error',
                        'message' => $validator_edit->errors()->first()
                    ], 405);
                }
            }


            // Edit image and text question
            $question = $question::where('id', $question_id)->first();
            $question->quiz_id = $quiz_id;
            $question->type_id = $type_id;
            $question->text =  $text;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    if (Storage::exists($question->path)) {
                        Storage::delete($question->path);
                    }

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('/public/files/question_images/', $fileName);
                    $filePathServer = asset('/storage/files/question_images/' . $fileName);

                    $question->path = $filePath;
                    $question->link_image = $filePathServer;
                }
            } else {
                if ($request->input('image')) {
                    echo false;
                } elseif (empty($request->hasFile('image'))) {
                    if (Storage::exists($question->path)) {
                        Storage::delete($question->path);
                    }
                    $question->path = '';
                    $question->link_image = '';
                }
            }
            $question->save();

            // foreach deletion images and answers
            if ($array_delete_answers) {
                foreach ($array_delete_answers as $key => $item) {
                    if (!empty($item)) {
                        $answer_single = $answer::where('id', $item)->first();
                        if (Storage::exists($answer_single->path)) {
                            Storage::delete($answer_single->path);
                            $answer::where('id', $item)->delete();
                        }
                    }
                }
            }

            // foreach add new answers
            if ($array_answers) {
                foreach ($array_answers as $key => $item) {

                    $new_id =  Uuid::uuid4()->toString();
                    $answer = new Answer();
                    $answer->id = $new_id;
                    $answer->user_id = $user_id;
                    $answer->question_id = $question_id;
                    $answer->text = $item->text;
                    $answer->answer_type = $item->answer_type;
                    $answer->path = '';
                    $answer->link_image = '';

                    if (isset($array_images)) {
                        foreach ($array_images as $key => $image) {

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
            }

            //foreach edit answers
            if ($array_answers_edit) {
                foreach ($array_answers_edit as $key => $item) {
                    $id_answer = $answer::where('id', $item['index'])->first();
                    $id_answer->text = $item['text'];
                    $id_answer->answer_type = $item['answer_type'];
                    $id_answer->save();

                    if ($array_images) {
                        foreach ($array_images as $key => $image) {

                            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                            $exp = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                            if ($item['index'] === $filename) {
                                error_log($item['index']);

                                if (Storage::exists($id_answer['path'])) {
                                    Storage::delete($id_answer['path']);

                                    $new_name_file = time() . '_' . $id_answer->id . '.' . $exp;

                                    $filePath = $image->storeAs('/public/files/answer_images/', $new_name_file);

                                    $link_image = asset('/storage/files/answer_images/' . $new_name_file);

                                    $id_answer->path = $filePath;
                                    $id_answer->link_image = $link_image;
                                    $id_answer->save();
                                }
                            } else {
                                if ($item['delete_image'] === 1) {
                                    if (Storage::exists($id_answer['path'])) {
                                        Storage::delete($id_answer['path']);
                                    }
                                    $id_answer->path = '';
                                    $id_answer->link_image = '';
                                    $id_answer->save();
                                }
                            }
                        }
                    } else {
                        if ($item['delete_image'] === 1) {
                            if (Storage::exists($id_answer['path'])) {
                                Storage::delete($id_answer['path']);
                            }
                            $id_answer->path = '';
                            $id_answer->link_image = '';
                            $id_answer->save();
                        }
                    }
                }
            }


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
