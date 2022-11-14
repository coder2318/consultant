<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{ApplicationController, ProfileController, CategoryController, ResumeController,
    ExperienceController, ResponseController, Commentcontroller, AuthController, Payment\PaymentController,
    SkillController, ResourceController, NotificationController, ReviewController};

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

Route::group(['prefix' => 'v1',  'middleware' => ['api']], function() {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('auth/get-info', [AuthController::class, 'me']);

    Route::group(
        [
            'middleware' =>['auth:api'],
        ] , function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        /** admin roli uchun */
        Route::group(
            [
                'middleware' =>['role:admin'],
            ] , function () {
            Route::get('admin/application/{application}', [ApplicationController::class, 'showAdmin']);
            Route::get('admin/notification/{notification}', [NotificationController::class, 'showAdmin']);
            Route::apiResource('category', CategoryController::class)->except('index');
        });

        /** consultant roli uchun */
        Route::group(
            [
                'middleware' =>['auth:api', 'role:consultant'],
            ] , function () {
            Route::get('category/check/list', [CategoryController::class, 'checkList']);
            Route::get('my-response', [ApplicationController::class, 'myResponses']); // mening otkliklarim
            Route::get('response-check/{application_id}', [ResponseController::class, 'responseCheck']); // applicationga otklik bosganligini tekshiradigan api
            Route::get('self-application', [ApplicationController::class, 'selfIndex']); // consultant uchun categorysiga mos bogan takliflar
            Route::get('my-order-application', [ApplicationController::class, 'myOrderIndex']); // consultant uchun mening zakazlarim
            Route::get('self-category', [CategoryController::class, 'selfCategories']);
            Route::get('my-resume', [ResumeController::class, 'myIndex']);
            Route::apiResource('resume', ResumeController::class);
            Route::apiResource('experience', ExperienceController::class);
            Route::apiResource('response', ResponseController::class);
        });

        /** user roli uchun */
        Route::group(
            [
                'middleware' =>['auth:api', 'role:user'],
            ] , function () {
                Route::get('my-application', [ApplicationController::class, 'myIndex']);
                Route::get('response-chat/{response}', [ResponseController::class, 'responseChat']);
                Route::apiResource('application', ApplicationController::class);
                Route::apiResource('comment', Commentcontroller::class);
                Route::apiResource('payment', PaymentController::class);
        });
        Route::get('my-notification', [NotificationController::class, 'myIndex']);
        Route::get('review-list/{resume_id}', [ReviewController::class, 'index']);
        /** authga kirgan apilar */
        Route::apiResource('profile', ProfileController::class);
        Route::apiResource('skill', SkillController::class);
        Route::apiResource('notification', NotificationController::class);
        Route::apiResource('review', ReviewController::class)->except('index','update');

    });
    /** umumiy apilar */
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('admin-category', [CategoryController::class, 'adminIndex']);
    Route::get('top-category', [CategoryController::class, 'topCategories']);
    Route::get('select-category', [CategoryController::class, 'selectCategory']);
    Route::get('translate/{lang}', [ResourceController::class, 'translate']);
    Route::get('language', [ResourceController::class, 'language']);
    Route::get('language/default', [ResourceController::class, 'languageDefault']);

});