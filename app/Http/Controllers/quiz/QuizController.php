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

class QuizController extends Controller
{

    public function GetQuiz(Quiz $quiz)
    {
        try {
            return $quiz::all();
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
            $description = $request->input('description');
            $file = $request->file('image');
            $formatBytes = new Functions();

            $validator_one = Validator::make($request->all(), [
                "image" => "required"
            ], [
                "required" => "Pole :attribute nie może być puste!",
            ]);

            if ($validator_one->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator_one->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $validator_two = Validator::make($request->all(), [
                "name" => "required|min:10|max:40",
                "description" => "required|min:20|max:200",
                "image" => "mimes:jpeg,png,jpg|between:0,5120"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
                'max' => 'Pole :attribute może mieć maksylamnie :max znaków!',
                'mimes' => 'Wymagane rozszerzenia to jpg, jpeg i png, a jest załadowne: ' . $file->getClientOriginalExtension(),
                'between' => 'Zdjęcie waży: ' . $formatBytes->formatBytes($file->getSize()) . ', a musi ważyć od 0 do 5M!'
            ]);

            if ($validator_two->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator_two->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('/public/files/quiz_files/', $fileName);
            $filePathServer = asset('/storage/files/quiz_files/' . $fileName);

            $id_quiz = Uuid::uuid4()->toString();

            $quiz->id = $id_quiz;
            $quiz->name = $name;
            $quiz->description = $description;
            $quiz->image_path = $filePath;
            $quiz->link_image = $filePathServer;

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
            $name = $request->input('name');
            $description = $request->input('description');
            $file = $request->file('image');
            $formatBytes = new Functions();

            $array_with_image = [
                "id" => "required|uuid|exists:quiz_table,id",
                "name" => "required|min:10|max:40",
                "description" => "required|min:20|max:200",
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

            $validator = Validator::make($request->all(), [
                "id" => "required|uuid|exists:quiz_table,id"
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
