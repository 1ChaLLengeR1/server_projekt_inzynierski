<?php

namespace App\Http\Controllers\game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameResult;
use Throwable;

class GetResults extends Controller
{
    public function GetResult(Request $request, GameResult $result)
    {
        try {
            $limit = $request->input('limit');

            if ($limit === null) {
                $result = $result::orderBy('result', 'asc')->get();
            } else {
                $result = $result::orderBy('result', 'asc')->limit($limit)->get();
            }

            return response()->json($result, 200);
        } catch (Throwable $e) {
            return response()->json([
                "status_code" => 500,
                "status" => "error",
                "message" => "Błąd w sekcji pobierania wyników!",
                "message_server" => $e->getMessage()
            ], 500);
        }
    }
}
