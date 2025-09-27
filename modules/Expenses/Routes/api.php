<?php

use Illuminate\Support\Facades\Route;
use Modules\Expenses\Http\Controllers\ExpenseController;


Route::get('expenses/filter', [ExpenseController::class, 'filter']);
Route::apiResource('expenses', ExpenseController::class);