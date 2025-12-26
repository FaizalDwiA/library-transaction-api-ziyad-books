<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowTransactionController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/borrow', [BorrowTransactionController::class, 'store']);

// Route POST /borrow
// Middleware 'auth:sanctum' dipasang untuk memastikan hanya user yang login yang bisa mengakses endpoint ini
// Pendekatan ini dipilih untuk mengamankan API dari akses tidak sah
Route::middleware('auth:sanctum')->post('/borrow', [BorrowTransactionController::class, 'store']);

