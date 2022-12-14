<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{ApplicationController, ProfileController, CategoryController, ResumeController,
    ExperienceController, ResponseController, Commentcontroller, AuthController, Payment\PaymentController,
    SkillController, ResourceController, NotificationController, ReviewController, Chat\ChatController, Chat\ChatMessageController, Chat\VideoChatController};

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
            Route::post('create-chat', [ResponseController::class, 'storeChat']); // private application keganda chat yrataish uchun api
            Route::get('self-application', [ApplicationController::class, 'selfIndex']); // consultant uchun categorysiga mos bogan takliflar
            Route::get('my-order-application', [ApplicationController::class, 'myOrderIndex']); // consultant uchun mening zakazlarim
            Route::get('my-application-count', [ApplicationController::class, 'countBadges']); // filterga countlarni chiqarib beruvchi badge api
            Route::get('self-category', [CategoryController::class, 'selfCategories']);
            Route::get('my-resume', [ResumeController::class, 'myIndex']);
            Route::put('resume/{resume}', [ResumeController::class, 'update']);
            Route::apiResource('resume', ResumeController::class)->except('update', 'index', 'show');
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
        /** authga kirgan apilar */
        Route::get('my-notification', [NotificationController::class, 'myIndex']);
        Route::get('review-list/{resume_id}', [ReviewController::class, 'index']);
        Route::get('check-has-resume', [ResumeController::class, 'checkHasResume']); // resume bor yoki yoligini tekshiradigan api

        /** Chat routes */
        Route::prefix('chat-messages')->group(function () {
            Route::get('/', [ChatMessageController::class, 'index']);
            Route::post('/send', [ChatMessageController::class, 'send']);
            Route::put('/update-showed', [ChatMessageController::class, 'updateShowed']);
        });
        Route::get('/consultant-chats', [ChatController::class, 'indexConsultant']);
        Route::prefix('chats')->group(function () {
            Route::get('/', [ChatController::class, 'index']);
            Route::get('/{chat_id}', [ChatController::class, 'show']);
            Route::post('/', [ChatController::class, 'store']);
        });

        /** video chat routes. Endpoints to alert call or receive call */

        Route::post('/video/call-user', [VideoChatController::class, 'callUser']);
        Route::post('/video/accept-call', [VideoChatController::class, 'acceptCall']);
        Route::post('/video/disconnect-call', [VideoChatController::class, 'disconnectCall']);
        Route::post('/video/decline-call', [VideoChatController::class, 'declineCall']);
        Route::post('/video/invite-to-chat/{chat_id}', [VideoChatController::class, 'inviteChat']); /** chatga chaqirvolish uchun api */
        Route::post('/video/action-in-chat', [VideoChatController::class, 'actionInChat']); /** chatga chaqirganda accept yoki now_now bosgandagi api */
        /**  */
        Route::get('make-all-showed-notification', [NotificationController::class, 'allShowed']); /** hamma notificationlarni showed qilib belgilash uchun */
        Route::post('cancel-response', [ResponseController::class, 'cancelResponse']);
        Route::apiResource('profile', ProfileController::class);
        Route::apiResource('skill', SkillController::class);
        Route::apiResource('notification', NotificationController::class);
        Route::apiResource('review', ReviewController::class)->except('index','update');

    });
    /** umumiy apilar */
    Route::get('statistic-count', [ApplicationController::class, 'countStatistic']);
    Route::get('resume-shortlist', [ResumeController::class, 'indexShortList']);
    Route::get('top-consultant', [ResumeController::class, 'topConsultant']);
    Route::get('resume/{resume}', [ResumeController::class, 'show']);
    Route::get('resume', [ResumeController::class, 'index']);
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('skill-list', [SkillController::class, 'indexList']);
    Route::get('admin-category', [CategoryController::class, 'adminIndex']);
    Route::get('top-category', [CategoryController::class, 'topCategories']);
    Route::get('select-category', [CategoryController::class, 'selectCategory']);
    Route::get('translate/{lang}', [ResourceController::class, 'translate']);
    Route::get('language', [ResourceController::class, 'language']);
    Route::get('language/default', [ResourceController::class, 'languageDefault']);

});
