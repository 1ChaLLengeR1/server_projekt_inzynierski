<?php
# auth
use App\Http\Controllers\auth\AuthControler;

# quiz
use App\Http\Controllers\quiz\QuizController;

# type
use App\Http\Controllers\type_question\TypeController;

# user
use App\Http\Controllers\user\UserController;

#question
use App\Http\Controllers\question\AddController;
use App\Http\Controllers\question\GetAllController;
use App\Http\Controllers\question\DeleteControler;
use App\Http\Controllers\question\GetController;
use App\Http\Controllers\question\EditController;
use App\Http\Controllers\question\GetTypeQuestion;

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

    Route::get('routers/http/controllers/quiz/get_all_quizzes', [QuizController::class, 'GetAll']);

    Route::group(["middleware" => "auth:api"], function () {

        Route::post('routers/http/controllers/quiz/get_single_quiz', [QuizController::class, 'GetSingleQuiz']);

        Route::post('routers/http/controllers/quiz/get_quiz', [QuizController::class, 'GetQuiz']);

        Route::post('routers/http/controllers/quiz/add_quiz', [QuizController::class, 'AddQuiz']);

        Route::post('routers/http/controllers/quiz/edit_quiz', [QuizController::class, 'EditQuiz']);

        Route::delete('routers/http/controllers/quiz/delete_quiz', [QuizController::class, 'DeleteQuiz']);
    });

    //Type request
    Route::get('routers/http/controllers/type_question/get_types', [TypeController::class, 'GetType']);

    Route::group(["middleware" => "auth:api"], function () {
        Route::post('routers/http/controllers/type_question/add_type', [TypeController::class, 'AddType']);

        Route::put('routers/http/controllers/type_question/edit_type', [TypeController::class, 'EditType']);

        Route::delete('routers/http/controllers/type_question/delete_type', [TypeController::class, 'DeleteType']);
    });

    // Question request
    Route::post('routers/http/controllers/question/get_all_questions', [GetAllController::class, 'GetAllQuestions']);

    Route::group(["middleware" => "auth:api"], function () {

        Route::post('routers/http/controllers/question/get_single_question', [GetController::class, 'GeqSingleQuestion']);

        Route::post('routers/http/controllers/question/get_type_question', [GetTypeQuestion::class, 'GetTypeQuestion']);

        Route::post('routers/http/controllers/question/add_questions', [AddController::class, 'AddQuestion']);

        Route::post('routers/http/controllers/question/edit_question', [EditController::class, 'EditQuestion']);

        Route::delete('routers/http/controllers/question/delete_question', [DeleteControler::class, 'DeleteQuestion']);
    });
} catch (Throwable $e) {
    return response()->json([
        "status" => "error",
        "message" => "Błąd ze strony serwera!",
        "message_server" => $e->getMessage()
    ]);
}
