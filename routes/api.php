<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactApprovalsApiController;
use App\Http\Controllers\Api\ContactNotesApiController;
use App\Http\Controllers\Api\ContactsApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\GroupsApiController;
use App\Http\Controllers\Api\TagsApiController;
use App\Http\Controllers\Api\UsersApiController;
use App\Support\ApiUserPayload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:10,1')->name('api.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth:sanctum', 'throttle:120,1'])->name('api.')->group(function () {
    Route::get('/user', fn (Request $r) => ApiUserPayload::for($r->user()))->name('user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardApiController::class, 'index'])->name('dashboard');

    // Approval workflow — must be registered before the contacts apiResource
    // so "pending" isn't swallowed by the {contact} show route.
    Route::get('/contacts/pending', [ContactApprovalsApiController::class, 'pending'])->name('contacts.pending');
    Route::post('/contacts/{contact}/approve', [ContactApprovalsApiController::class, 'approve'])->name('contacts.approve');
    Route::post('/contacts/{contact}/reject', [ContactApprovalsApiController::class, 'reject'])->name('contacts.reject');
    Route::post('/contact-edit-requests/{editRequest}/approve', [ContactApprovalsApiController::class, 'approveEdit'])->name('edit-requests.approve');
    Route::post('/contact-edit-requests/{editRequest}/reject', [ContactApprovalsApiController::class, 'rejectEdit'])->name('edit-requests.reject');

    Route::post('/contacts/{contact}/suspend', [ContactsApiController::class, 'suspend'])->name('contacts.suspend');
    Route::post('/contacts/{contact}/ban', [ContactsApiController::class, 'ban'])->name('contacts.ban');
    Route::post('/contacts/{contact}/reactivate', [ContactsApiController::class, 'reactivate'])->name('contacts.reactivate');
    Route::post('/contacts/{contact}/rate', [ContactsApiController::class, 'rate'])->name('contacts.rate');

    Route::post('/contacts/{contact}/notes', [ContactNotesApiController::class, 'store'])->name('contacts.notes.store');
    Route::put('/contacts/{contact}/notes/{note}', [ContactNotesApiController::class, 'update'])->name('contacts.notes.update');
    Route::delete('/contacts/{contact}/notes/{note}', [ContactNotesApiController::class, 'destroy'])->name('contacts.notes.destroy');

    Route::apiResource('contacts', ContactsApiController::class);

    Route::get('/groups', [GroupsApiController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupsApiController::class, 'store'])->name('groups.store');
    Route::put('/groups/{group}', [GroupsApiController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupsApiController::class, 'destroy'])->name('groups.destroy');

    Route::get('/tags', [TagsApiController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagsApiController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}', [TagsApiController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagsApiController::class, 'destroy'])->name('tags.destroy');

    Route::get('/users', [UsersApiController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersApiController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UsersApiController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UsersApiController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersApiController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/lock', [UsersApiController::class, 'lock'])->name('users.lock');
    Route::post('/users/{user}/unlock', [UsersApiController::class, 'unlock'])->name('users.unlock');
    Route::put('/users/{user}/password', [UsersApiController::class, 'changePassword'])->name('users.password');
});
