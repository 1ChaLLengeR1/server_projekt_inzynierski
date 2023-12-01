<?php

namespace App\Http\Controllers\quiz;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\HelperFunctions\Functions;
use Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class QuizController extends Controller
{

    public function GetAll(Request $request, Quiz $quiz)
    {
        try {
            return response()->json($quiz::all(), 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania wszystkich quizów!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function GetSingleQuiz(Request $request, Quiz $quiz)
    {
        try {
            $quiz_id = $request->input('quiz_id');
            $user_id = $request->input('user_id');
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "user_id" => "required",
                "quiz_id" => "required|uuid|exists:quiz_table,id"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "id musi być poprawnie zapisane!",
                'exists' => 'Brak takie id quizu!',
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry Id!"
                ], Response::HTTP_BAD_REQUEST);
            }

            $single_quiz = $quiz::where('id', $quiz_id)->first();
            return response()->json($single_quiz, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania pojedynczego quizu!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function GetQuiz(Request $request, Quiz $quiz)
    {
        try {
            $user_id = $request->input("user_id");
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "user_id" => "required",
            ], [
                "required" => "Pole :attribute nie może być puste!",
            ]);
            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry Id!"
                ], Response::HTTP_BAD_REQUEST);
            }

            $quiz = Quiz::where('user_id', $user_id)->get();

            return response()->json($quiz, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania quizów!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function AddQuiz(Request $request, Quiz $quiz)
    {
        try {
            $name = $request->input('name');
            $user_id = $request->input('user_id');
            $description = $request->input('description');
            $file = $request->file('image');
            $formatBytes = new Functions();
            $comparison = new Functions();

            $array_with_image = [
                "user_id" => "required",
                "name" => "required|min:10|max:40",
                "description" => "required|min:20|max:400"
            ];

            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                'max' => 'Pole :attribute może mieć maksylamnie :max znaków!'
            ];

            if ($file) {
                $array_with_image = [
                    "user_id" => "required",
                    "name" => "required|min:10|max:40",
                    "description" => "required|min:20|max:400",
                    "image" => "mimes:jpeg,png,jpg|between:0,5120"
                ];

                $array_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                    'max' => 'Pole :attribute może mieć maksylamnie :max znaków!',
                    'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $file->getClientOriginalExtension(),
                    'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($file->getSize()) . ', a musi ważyć od 0 do 5M!'
                ];
            }

            $validator = Validator::make($request->all(), $array_with_image, $array_message);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry Id!"
                ], Response::HTTP_BAD_REQUEST);
            }


            if ($file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('/public/files/quiz_files/', $fileName);
                $filePathServer = asset('/storage/files/quiz_files/' . $fileName);
                $quiz->image_path = $filePath;
                $quiz->link_image = $filePathServer;
            } else {
                $quiz->image_path = '';
                $quiz->link_image = '';
            }


            $id_quiz = Uuid::uuid4()->toString();
            $quiz->id = $id_quiz;
            $quiz->user_id = $user_id;
            $quiz->name = $name;
            $quiz->description = $description;


            $quiz->save();

            return response()->json([
                "id_quiz" => $id_quiz,
                "status_code" => 201,
                "status" => "success",
                "message" => "Poprawnie stworzono quiz!",

            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji tworzenia quizu!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function EditQuiz(Request $request, Quiz $quiz)
    {
        try {

            $id = $request->input("id");
            $user_id = $request->input("user_id");
            $name = $request->input('name');
            $description = $request->input('description');
            $file = $request->file('image');
            $formatBytes = new Functions();
            $comparison = new Functions();

            $array_with_image = [
                "id" => "required|uuid|exists:quiz_table,id",
                "user_id" => "required",
                "name" => "required|min:10|max:40",
                "description" => "required|min:20|max:400",
            ];
            $array_message = [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "id musi być poprawnie zapisane!",
                'exists' => 'Brak takie id quizu!',
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                'max' => 'Pole :attribute może mieć maksylamnie :max znaków!',
            ];

            if ($file) {
                $array_with_image = [
                    "id" => "required|uuid|exists:quiz_table,id",
                    "user_id" => "required",
                    "name" => "required|min:10|max:40",
                    "description" => "required|min:20|max:200",
                    "image" => "mimes:jpeg,png,jpg|between:0,5120"
                ];

                $array_message = [
                    "required" => "Pole :attribute nie może być puste!",
                    "uuid" => "id musi być poprawnie zapisane!",
                    'exists' => 'Brak takie id quizu!',
                    'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                    'max' => 'Pole :attribute może mieć maksylamnie :max znaków!',
                    'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $file->getClientOriginalExtension(),
                    'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($file->getSize()) . ', a musi ważyć od 0 do 5M!'
                ];
            }

            $validator_two = Validator::make($request->all(), $array_with_image, $array_message);

            if ($validator_two->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator_two->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry Id!"
                ], Response::HTTP_BAD_REQUEST);
            }

            $quiz = $quiz::where('id', $id)->first();


            if ($file) {
                if (Storage::exists($quiz->image_path)) {
                    Storage::delete($quiz->image_path);
                } else {
                    return response()->json([
                        "status_code" => 401,
                        "status" => "error",
                        "message" => "Błąd podczas usuwania zdjęcia przed edycją. Sprawdz server, bądź powiadom o tym administratora!"
                    ], 401);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('/public/files/quiz_files/', $fileName);
                $filePathServer = asset('/storage/files/quiz_files/' . $fileName);

                $quiz->image_path = $filePath;
                $quiz->link_image = $filePathServer;
            }

            $quiz->name = $name;
            $quiz->description = $description;

            $quiz->save();

            return response()->json([
                "status_code" => "200",
                "status" => "success",
                "message" => "Poprawnie z modyfikowano quiz!"
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji edytowania quizu!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function DeleteQuiz(Request $request, Quiz $quiz)
    {
        try {
            $id_quiz = $request->input('id');
            $user_id = $request->input('user_id');
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "id" => "required|uuid|exists:quiz_table,id",
                "user_id" => "required"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                "uuid" => "id musi być poprawnie zapisane!",
                'exists' => 'Brak takie id quizu!'
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();

            if (!$comparison->ComparisonId($apy['sub'], $user_id)) {
                return response()->json([
                    "status_code" => 401,
                    'status' => 'error',
                    'message' => "Nie poprawne parametry Id!"
                ], Response::HTTP_BAD_REQUEST);
            }

            $delete_quiz = $quiz::where('id', $id_quiz)->first();

            if (Storage::exists($delete_quiz->image_path)) {
                Storage::delete($delete_quiz->image_path);
                $quiz::where('id', $id_quiz)->delete();
            } else {
                return response()->json([
                    "status_code" => 401,
                    "status" => "error",
                    "message" => "Zdjęcie w folderze nie zgadza się z ściężką, która jest podawana, a to powoduje krytyczny błąd!!",
                ], 401);
            }

            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "Poprawnie usunięto quiz!",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji usuwania quizu!",
                "message_server" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
