<?php

use App\Http\Controllers\API\V1\CampaignController;
use App\Http\Controllers\API\V1\IpWhitelistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\V1\LeadController;
use App\Http\Controllers\API\V1\StatsController;
use App\Http\Controllers\API\V1\UserController;
use App\Models\Campaign;

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

// Route::middleware(['auth:sanctum', 'allow.unverified.users'])->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group(
//     ['prefix' => 'v1'],
//     function () {
//         Route::get('/users', [UserController::class, 'index']);
//         Route::get('/user', [UserController::class, 'getUser']);
//     }
// );
Route::group(
    ['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'twofactor']],
    function () {
        Route::get('/user', [UserController::class, 'getUser']);
        Route::get('/agents',  [UserController::class, 'index']);
        Route::post('/agents', [UserController::class, 'store']);
        Route::delete('/agents/{id}', [UserController::class, 'destroy']);
    }
);
// Apply the IP whitelist middleware only after successful login and 2FA
Route::group(
    ['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'twofactor', 'ip.whitelist']],
    function () {
        Route::post('/verify', [AuthController::class, 'store']);
        Route::post('/verify/resend', [AuthController::class, 'resend']);
        Route::get('/campaigns', [CampaignController::class, 'index']);
        Route::post('/campaigns', [CampaignController::class, 'store']);
        Route::get('/leads', [LeadController::class, 'index']);
        Route::get('/leads/{id}', [LeadController::class, 'show']);
        Route::patch('/leads/{id}/read', [LeadController::class, 'setLeadAsRead']);
        Route::delete('/leads/{id}', [LeadController::class, 'destroy']);
        Route::get('/stats', [StatsController::class, 'getDashboardStats']);
        Route::get('/stats/user/{id}', [StatsController::class, 'userStats']);

        // Route::get('/stats/monthly', [StatsController::class, 'monthlyLeadSummary']);
    }
);


Route::group(['prefix' => 'v1',], function () {
    Route::get('/asd/{id}',  [LeadController::class, 'getindex']);
    Route::get('/stats/user-activities', [StatsController::class, 'getUserActivitiesStats']);
    Route::post('/add_lead', [LeadController::class, 'store']);
    Route::get('/delete_lead/{id}', [LeadController::class, 'destroy']);
    Route::get('/delete_lead/{id}/{force?}', [LeadController::class, 'forceDelete']);

    Route::get('/sellers',  [UserController::class, 'getSellers']);
});

Route::group(
    ['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'role_or_permission:admin']],
    function () {
        Route::get('/whitelist', [IpWhitelistController::class, 'index']);
        Route::post('/whitelist', [IpWhitelistController::class, 'store']);
        Route::delete('/whitelist/{id}', [IpWhitelistController::class, 'destroy']);
    }
);
