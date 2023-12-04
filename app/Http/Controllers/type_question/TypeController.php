<?php

namespace App\Http\Controllers\type_question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type_Quiz;
use Ramsey\Uuid\Uuid;
use Throwable;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{

    public function GetType(Request $request, Type_Quiz $type_quiz)
    {
        try {
            return response()->json($type_quiz::all(), 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji pobierania typów pytań!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }

    public function AddType(Request $request, Type_Quiz $type_quiz)
    {

        try {
            $name = $request->input('name');
            $descrition = $request->input('description');
            $type = $request->input('type');

            $validator = Validator::make($request->all(), [
                "name" => "required|min:5",
                "description" => "required|min:10",
                "type" => "required"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $type_quiz->id = Uuid::uuid4()->toString();
            $type_quiz->name = $name;
            $type_quiz->description = $descrition;
            $type_quiz->type = $type;

            $type_quiz->save();
            return response()->json([
                "status_code" => 201,
                "status" => "success",
                "message" => "Poprawnie stworzono quiz!",

            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji dodawania typu!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }

    public function EditType(Request $request, Type_Quiz $type_quiz)
    {

        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $descrition = $request->input('description');
            $type = $request->input('type');

            $validator = Validator::make($request->all(), [
                "id" => "required|uuid|exists:type_table,id",
                "name" => "required|min:5",
                "description" => "required|min:10",
                "type" => "required"
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'exists' => 'Brak takie id quizu!',
                "uuid" => "id musi być poprawnie zapisane!",
                'min' => 'Pole :attribute musi mieć minimum :min znaków!',
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $type_quiz = $type_quiz::where('id', $id)->first();
            $type_quiz->name = $name;
            $type_quiz->description = $descrition;
            $type_quiz->type = $type;
            $type_quiz->save();

            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "Poprawnie z modyfikowano typ!",

            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji modyfikacji typu!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }

    public function DeleteType(Request $request, Type_Quiz $type_quiz)
    {
        try {
            $id = $request->input('id');
            $validator = Validator::make($request->all(), [
                "id" => "required|uuid|exists:type_table,id",
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'exists' => 'Brak takie id quizu!',
                "uuid" => "id musi być poprawnie zapisane!",
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    "status_code" => 400,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $type_quiz::where('id', $id)->delete();
            return  response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "Poprawnie usunięto typ!",
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji usuwania typu!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
