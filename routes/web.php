<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\CostingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

// --------------- Base Controller --------------- //
// views
Route::get('/', [BaseController::class,'dashboard']);
Route::get('login', [BaseController::class,'login']);

// requests
Route::get('getLogin', [BaseController::class,'getLogin']);
Route::get('endsession', [BaseController::class,'endsession']);

// --------------- Costing Controller --------------- //
// views
Route::get('costing/{ID}/{edit?}', [CostingController::class, 'costing'])->name('costing.show');

// requests
Route::get('getUserCostingSheetsHeaders', [CostingController::class,'getUserCostingSheetsHeaders']);
Route::get('getUserCostingSheetHeaderById', [CostingController::class,'getUserCostingSheetHeaderById']);
Route::get('getUserCostingSheetLinesByHeaderId', [CostingController::class,'getUserCostingSheetLinesByHeaderId']);
Route::get('getProducts', [CostingController::class,'getProducts']);
Route::post('createCostingSheet', [CostingController::class,'createCostingSheet']);
Route::post('postUpdateCostingSheet', [CostingController::class,'postUpdateCostingSheet']);
Route::post('postReviseCostingSheet', [CostingController::class,'postReviseCostingSheet']);

// --------------- Admin Controller --------------- //
Route::get('admin', [AdminController::class,'admin']);
Route::post('postTradeTermsCrud', [AdminController::class,'postTradeTermsCrud']);
Route::post('postWarehouseCostsCrud', [AdminController::class,'postWarehouseCostsCrud']);

// --------------- API testing  --------------- //
//not functional yet
Route::get('api/{file}', function ($file) {
    $filePath = base_path('api/' . $file);

    if (file_exists($filePath) && is_file($filePath)) {
        // Return the file using the response() function
        return Response::file($filePath);
    } else {
        abort(404); // File not found
    }
});