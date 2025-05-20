<?php

use App\Http\Controllers\API\V1\IpWhitelistController;
use App\Http\Controllers\API\V1\StatsController;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware([ 'twofactor'])->name('dashboard');
require __DIR__ . '/auth.php';

Route::get('/send-test-email', function () {
    Mail::to('dev.exceldigitalgroup@gmail.com')->send(new TestMail());
    return 'Test email sent!';
});
Route::get('/my-ip', function (Request $request) {
    $ip = $request->ip(); // Get the client's IP

    return $ip; // Return IP in response
});

Route::get('/stats',  [StatsController::class, 'getStats']);

Route::middleware('ip.whitelist')->get('/ips', [IpWhitelistController::class, 'index']);
