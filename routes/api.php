<?php


use App\Http\Controllers\ActivePlanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/plans', MembershipPlanController::class);

    Route::get('/members/counts', [MemberController::class, 'counts']);
    Route::apiResource('/members', MemberController::class);

    Route::get('/sales/counts', [SaleController::class,'counts']);
    Route::apiResource('/sales', SaleController::class);

    Route::apiResource('/services', ServiceController::class);
    Route::apiResource('/payment-methods', PaymentMethodController::class);

    Route::get('/transactions/counts', [TransactionController::class, 'counts']);
    Route::apiResource('/transactions', TransactionController::class);

    Route::get('/expenses/counts', [ExpenseController::class, 'counts']);
    Route::apiResource('/expenses', ExpenseController::class);

    Route::get('/users', [AuthController::class,'index']);
    Route::post('/users/update', [AuthController::class,'update']);
    Route::put('/users/change-password', [AuthController::class,'changePassword']);
    Route::post('/users/register', [AuthController::class, 'register']);

    Route::post('/active-plans/{active_plan}/deposit',[ActivePlanController::class, 'addPaymentOnPlan']);
    Route::apiResource('/active-plans', ActivePlanController::class);
});
