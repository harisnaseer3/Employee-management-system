<?php

use App\Http\Controllers\api\WhatsApp\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\EmployeeController;
use App\Http\Controllers\api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/whatsapp-message', [WhatsAppController::class, 'sendMessage']);

/*
 * Auth
 */
Route::post('/register', [AuthController::class, 'registerUser']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

//Route::middleware(['auth:api', 'verified'])->group(function () {
//
//    Route::post('logout', [AuthController::class, 'logout']);
//
//    /*
//     * Employee
//     */
    Route::apiResource('/employee', EmployeeController::class);
//});
