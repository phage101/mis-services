<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // User & Role Management
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    // Ticketing Module
    Route::resource('tickets', \App\Http\Controllers\TicketController::class);
    Route::post('tickets/{ticket}/response', [\App\Http\Controllers\TicketController::class, 'addResponse'])->name('tickets.response');
    Route::get('api/request-types/{type}/categories', [\App\Http\Controllers\TicketController::class, 'getCategories'])->name('api.categories');

    // Meeting Scheduler
    Route::resource('meetings', \App\Http\Controllers\MeetingController::class);
    Route::get('api/meetings/check-conflict', [\App\Http\Controllers\MeetingController::class, 'checkConflict'])->name('api.meetings.conflict');
    Route::get('api/meetings/calendar-events', [\App\Http\Controllers\MeetingController::class, 'calendarEvents'])->name('api.meetings.events');

    // Event Management
    Route::get('events/reports', [\App\Http\Controllers\EventController::class, 'reports'])->name('events.reports');
    Route::resource('events', \App\Http\Controllers\EventController::class);
    Route::post('events/{event}/attendance', [\App\Http\Controllers\EventController::class, 'updateAttendance'])->name('events.attendance.update');
    Route::get('events/{event}/mark-attendance/{participant_uuid}', [\App\Http\Controllers\EventController::class, 'markAttendance'])->name('events.attendance.mark');
    Route::get('events/{event}/print-attendance', [\App\Http\Controllers\EventController::class, 'printAttendance'])->name('events.print-attendance');
});

// Public Event Registration
Route::get('events/{event}/register', [\App\Http\Controllers\EventController::class, 'registrationPage'])->name('events.register');
Route::post('events/{event}/register', [\App\Http\Controllers\EventController::class, 'register'])->name('events.register.submit');
