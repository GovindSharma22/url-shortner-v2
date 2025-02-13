<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InviteController;

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/short', [App\Http\Controllers\ShortUrlController::class, 'index'])->name('short');

Route::middleware(['auth'])->group(function () {
    Route::get('/shorturls', [ShortUrlController::class, 'index'])->name('shorturls.index');
  
    Route::get('/shorturls/create', [ShortUrlController::class, 'create'])->name('shorturls.create');
   
    Route::post('/shorturls', [ShortUrlController::class, 'store'])->name('shorturls.store');
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites/send', [InviteController::class, 'sendInvite'])->name('invites.send');
});

Route::get('/{code}', [ShortUrlController::class, 'redirect'])->name('shorturls.redirect');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\SuperAdminController;

//to be add
//Route::middleware(['auth', 'role:super_admin'])->group(function () {
    //Route::get('/superadmin/invites', [InviteController::class, 'index'])->name('superadmin.invites');
    Route::get('/superadmin/invites', [InviteController::class, 'index'])->name('superadmin.invite');

    Route::post('/superadmin/invites/send', [InviteController::class, 'sendInvite'])->name('superadmin.invites.send');

    //Route::get('/superadmin/shorturls', [SuperAdminController::class, 'viewShortUrls'])->name('superadmin.shorturls');
    Route::get('/superadmin/shorturls/export', [SuperAdminController::class, 'exportCsv'])->name('superadmin.shorturls.export');

    Route::get('/superadmin/client-stats', [SuperAdminController::class, 'clientStats'])->name('superadmin.client.stats');
//});

