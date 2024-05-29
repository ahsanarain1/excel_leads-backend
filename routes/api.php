<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\V1\LeadController;
use App\Http\Controllers\API\V1\StatsController;
use App\Http\Controllers\API\V1\UserController;

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
Route::group(
    ['prefix' => 'v1'],
    function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user', [UserController::class, 'getUser']);
    }
);

Route::middleware(['auth:sanctum', 'twofactor'])
    ->get(
        '/user',
        [UserController::class, 'getUser']
    );

Route::group(
    ['prefix' => 'v1', 'middleware' => 'auth:sanctum', 'twofactor'],
    function () {
        Route::post('/verify',  [AuthController::class, 'store']);

        Route::post('/verify/resend',  [AuthController::class, 'resend']);
        // Route::get('/leads/{lead}', [LeadController::class, 'show']);
        Route::get('/asd', function () {
            return response()->json(['message' => 'ssdd'], 200);
        });
    }
);
Route::group(['prefix' => 'v1',], function () {
    Route::get('/leads/{id}', [LeadController::class, 'show']);

    // Route to fetch all leads if no ID is provided
    Route::get('/leads', [LeadController::class, 'index']);

    Route::patch('/leads/{id}/read', [LeadController::class, 'setLeadAsRead']);

    Route::get('/stats', [StatsController::class, 'getLeadsStats']);
    Route::get('/stats/user-activities', [StatsController::class, 'getUserActivitiesStats']);
    Route::post('/add_lead', [LeadController::class, 'store']);
});
Route::middleware(['auth:sanctum', 'twofactor'])->get('/asd', function () {
    return response()->json(['message' => 'ssdd'], 200);
});
// Route to fetch a specific lead if ID is provided
