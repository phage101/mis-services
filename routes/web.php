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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset Routes
    Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
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
    Route::resource('offices', \App\Http\Controllers\OfficeController::class);
    Route::resource('divisions', \App\Http\Controllers\DivisionController::class);

    // Ticketing Module
    Route::resource('tickets', \App\Http\Controllers\TicketController::class);
    Route::post('tickets/{ticket}/response', [\App\Http\Controllers\TicketController::class, 'addResponse'])->name('tickets.response');
    Route::get('api/request-types/{type}/categories', [\App\Http\Controllers\TicketController::class, 'getCategories'])->name('api.categories');
    Route::get('api/offices/{office}/divisions', [\App\Http\Controllers\OfficeController::class, 'getDivisions'])->name('offices.divisions');

    // Meeting Scheduler
    Route::resource('meetings', \App\Http\Controllers\MeetingController::class);
    Route::get('api/meetings/check-conflict', [\App\Http\Controllers\MeetingController::class, 'checkConflict'])->name('api.meetings.conflict');
    Route::get('api/meetings/calendar-events', [\App\Http\Controllers\MeetingController::class, 'calendarEvents'])->name('api.meetings.events');

    // Event Management
    Route::get('events/reports', [\App\Http\Controllers\EventController::class, 'reports'])->name('events.reports');
    Route::resource('events', \App\Http\Controllers\EventController::class);
    Route::post('events/{event}/attendance', [\App\Http\Controllers\EventController::class, 'updateAttendance'])->name('events.attendance.update');
    Route::get('events/{event}/mark-attendance/{participant_uuid}', [\App\Http\Controllers\EventController::class, 'markAttendance'])->name('events.attendance.mark');
    Route::post('events/{event}/walk-in', [\App\Http\Controllers\EventController::class, 'addWalkIn'])->name('events.walk-in');
    Route::get('events/{event}/print-attendance', [\App\Http\Controllers\EventController::class, 'printAttendance'])->name('events.print-attendance');
});

// Public Event Registration
Route::get('events/{event}/register', [\App\Http\Controllers\EventController::class, 'registrationPage'])->name('events.register');
Route::post('events/{event}/register', [\App\Http\Controllers\EventController::class, 'register'])->name('events.register.submit')->middleware('throttle:5,1');

// Public Event Attendance (for Attendance-Only events)
Route::get('events/{event}/attend', [\App\Http\Controllers\EventController::class, 'attendancePage'])->name('events.attend');
Route::post('events/{event}/attend', [\App\Http\Controllers\EventController::class, 'submitAttendance'])->name('events.attend.submit')->middleware('throttle:10,1');

// Public Event Listing
Route::get('public-events', [\App\Http\Controllers\EventController::class, 'publicIndex'])->name('events.public.index');

// Public Service Desk
Route::get('service-desk', [\App\Http\Controllers\PublicTicketController::class, 'index'])->name('public.tickets.create');
Route::post('service-desk', [\App\Http\Controllers\PublicTicketController::class, 'store'])->name('public.tickets.store')->middleware('throttle:5,1');
Route::get('service-desk/track', [\App\Http\Controllers\PublicTicketController::class, 'track'])->name('public.tickets.track')->middleware('throttle:30,1');

// Public Meeting Request
Route::get('meeting-request', [\App\Http\Controllers\PublicMeetingController::class, 'index'])->name('public.meetings.create');
Route::post('meeting-request', [\App\Http\Controllers\PublicMeetingController::class, 'store'])->name('public.meetings.store')->middleware('throttle:5,1');
Route::get('meeting-request/track', [\App\Http\Controllers\PublicMeetingController::class, 'track'])->name('public.meetings.track')->middleware('throttle:30,1');
Route::get('api/public/users/search', [\App\Http\Controllers\PublicTicketController::class, 'searchUser'])->name('api.public.users.search')->middleware('throttle:20,1');
Route::get('api/public/request-types/{type}/categories', [\App\Http\Controllers\PublicTicketController::class, 'getCategories'])->name('api.public.categories');
Route::get('api/public/offices/{office}/divisions', [\App\Http\Controllers\PublicTicketController::class, 'getDivisions'])->name('api.public.divisions');

// Client Satisfaction Feedback
Route::get('feedback/{ticket}', [\App\Http\Controllers\ClientSatisfactionFeedbackController::class, 'create'])->name('csf.create');
Route::post('feedback/{ticket}', [\App\Http\Controllers\ClientSatisfactionFeedbackController::class, 'store'])->name('csf.store')->middleware('throttle:5,1');

Route::middleware('auth')->group(function () {
    Route::prefix('reports/csf')->name('reports.csf.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CsfReportController::class, 'index'])->name('index');
        Route::patch('{id}/sign', [\App\Http\Controllers\CsfReportController::class, 'sign'])->name('sign');
    });
});
