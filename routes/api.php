<?php

use App\Http\Controllers\auth\AuthControler;
use App\Http\Controllers\quiz\QuizController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

try {
    // Auth request
    Route::post('routers/http/controllers/auth/register', [AuthControler::class, 'register']);
    Route::post('routers/http/controllers/auth/login', [AuthControler::class, 'login']);

    Route::group([
        "middleware" => "auth:api"
    ], function () {
        Route::get('routers/http/controllers/user/get_users', [UserController::class, 'getUsers']);
        Route::get('routers/http/controllers/auth/refresh_token', [AuthControler::class, 'refreshToken']);
        Route::get('routers/http/controllers/auth/logout', [AuthControler::class, 'logout']);
    });

    //Quiuz request
    Route::group(["middleware" => "auth:api"], function () {
        Route::get('routers/http/controllers/quiz/get_quiz', [QuizController::class, 'GetQuiz']);

        Route::post('routers/http/controllers/quiz/add_quiz', [QuizController::class, 'AddQuiz']);

        Route::post('routers/http/controllers/quiz/edit_quiz', [QuizController::class, 'EditQuiz']);

        Route::delete('routers/http/controllers/quiz/delete_quiz', [QuizController::class, 'DeleteQuiz']);
    });
} catch (Throwable $e) {
    return response()->json([
        "status" => "error",
        "message" => "Błąd ze strony serwera!",
        "message_server" => $e->getMessage()
    ]);
}
