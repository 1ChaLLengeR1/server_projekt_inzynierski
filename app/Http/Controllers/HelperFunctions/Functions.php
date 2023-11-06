<?php

namespace App\Http\Controllers\HelperFunctions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Functions
{

    public function __constructor()
    {

    }

    public function checkIdParam($id, $paramsDataBase)
    {

        try {
            $paramsDataBase::where('id', $id)->first();
            return true;

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brak takiego id użytkownika!',
                'server_message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }

    }
}
