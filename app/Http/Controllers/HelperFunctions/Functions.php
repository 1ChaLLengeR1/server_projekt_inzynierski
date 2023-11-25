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

    public function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
