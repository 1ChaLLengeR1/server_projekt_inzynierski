<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Controllers\HelperFunctions\Functions;

class DeleteControler extends Controller
{
    public function DeleteQuestion(Request $request, Question $question, Answer $answer)
    {
        try {
            $id = $request->input('id');
            $user_id = $request->input('user_id');
            $comparison = new Functions();

            $validator = Validator::make($request->all(), [
                "id" => 'required|uuid|exists:question_table,id'
            ], [
                "required" => "Pole :attribute nie może być puste!",
                'exists' => 'Brak takiego id pytania!',
                "uuid" => "id musi być poprawnie zapisane!",
            ]);

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

            $delete_question = $question::where('id', $id)->first();
            $delete_answers = $answer::where('question_id', $id)->get();

            try {

                if (Storage::exists($delete_question->path)) {
                    Storage::delete($delete_question->path);
                    $delete_question::where('id', $id)->delete();

                    foreach ($delete_answers as $key => $item) {
                        if (Storage::exists($item['path'])) {
                            Storage::delete($item['path']);
                            error_log($item['id']);
                            $answer::where('question_id', $id)->delete();
                        }
                    }
                }
            } catch (Throwable $e) {
                return response()->json([
                    "status_code" => 500,
                    "status" => "error",
                    "message" => "Błąd usuwania zdjęcia w quiz_controller i answer_controller. Skontaktuj się z właścicielem!",
                    "message_server" => $e->getMessage()
                ], 401);
            }





            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "Poprawnie usunięto pytanie!",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w skecji usuwania pytania!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
