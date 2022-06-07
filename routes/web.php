<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/", [EmployeeController::class, "index"])->name('employee');
Route::get("/employee", [EmployeeController::class, "index"])->name('employee');
Route::get("/employee-list", [EmployeeController::class, "list"])->name('employee-list');

Route::post("/employee", [EmployeeController::class, "action"])->name('employee.action');
Route::post("/employee-store", [EmployeeController::class, "store"])->name('employee-store');
Route::delete('delete-multiple-employee', [EmployeeController::class, 'deleteMultiple'])->name('multiple-delete');