<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertiesController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;





Route::post("/login", [AdminController::class, 'login']);

Route::get("/property", [PropertiesController::class, 'properties']);
Route::post("/properties/filter", [PropertiesController::class, 'filter']);
Route::get("/property/{id}", [PropertiesController::class, 'propertiesByID'])->where('id', '[0-9]+');

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::post("/admin", [AdminController::class, 'store']);
    Route::get("/admin", [AdminController::class, 'get']);
    Route::get("/admin/all", [AdminController::class, 'getAllAdmin']);
    Route::patch("/admin/{id}", [AdminController::class, 'update'])->where('id', '[0-9]+');
    Route::delete("/admin/{id}", [AdminController::class, 'delete'])->where('id', '[0-9]+');
    Route::delete("/logout", [AdminController::class, 'logout'])->where('id', '[0-9]+');
    


    // Route::get("/property", [PropertiesController::class, 'properties']);
    Route::post("/property", [PropertiesController::class, 'addProperty']);
    Route::delete("/property/{id}", [PropertiesController::class, 'deleteProperty'])->where('id', '[0-9]+');
    // Route::get("/property/{id}", [PropertiesController::class, 'propertiesByID'])->where('id', '[0-9]+');
    Route::patch("/property/{id}", [PropertiesController::class, 'update'])->where('id', '[0-9]+');

    Route::patch("/property/image/{id}", [PropertiesController::class, 'updateImage'])->where('id', '[0-9]+');
    Route::post("/property/image/{id_property}", [PropertiesController::class, 'addImage'])->where('id_property', '[0-9]+');
    Route::delete("/property/image/{id}", [PropertiesController::class, 'deleteImage'])->where('id', '[0-9]+');
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

