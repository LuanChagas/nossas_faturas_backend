<?php

use App\Http\Controllers\CartaoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\PessoaController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/pessoa', [PessoaController::class,'index']);
Route::post('/pessoa', [PessoaController::class,'criar']);


Route::get('/cartoes', [CartaoController::class,'index']);
Route::post('/cartao', [CartaoController::class,'criar']);

Route::get('/fatura', [FaturaController::class,'index']);
Route::post('/fatura', [FaturaController::class,'criar']);
Route::get('/criarfaturames', [FaturaController::class,'criarFaturaMes']);

Route::get('/compra',[CompraController::class,'index']);
Route::post('/compra',[CompraController::class,'criar']);

