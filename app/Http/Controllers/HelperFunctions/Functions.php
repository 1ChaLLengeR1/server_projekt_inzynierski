<?php

namespace App\Http\Controllers\HelperFunctions;

use Illuminate\Support\Facades\Validator;
use Throwable;

class Functions
{

    public function __constructor()
    {

    }

    public function checkIdParam($request, $paramsDataBase)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
        ], [
            'required' => 'Pole :attribute jest puste!',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $paramsDataBase::where('id', $request->input('id'))->first();

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brak takiego id użytkownika!',
                'server_message' => $e->getMessage()
            ]);
        }

        return true;
    }
}
