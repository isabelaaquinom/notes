<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;


// auth routes - user not logged 
Route::middleware([CheckIsNotLogged::class])->group(function(){
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit']);
});

// auth routes - userlogged 
Route::middleware([CheckIsLogged::class])->group(function(){
    Route::get('/', [NoteController::class, 'index'])->name('home');
    Route::get('/newNote', [NoteController::class, 'newNote'])->name('new');
    Route::post('/newNoteSubmit', [NoteController::class, 'newNoteSubmit'])->name('newNoteSubmit');

    //edit note 
    Route::get('/editNote/{id}', [NoteController::class, 'editNote'])->name('edit');
    Route::post('/editNoteSubmit/{id}', [NoteController::class, 'editNoteSubmit'])->name('editNoteSubmit');
    //delete note 
    Route::get('/deleteNote/{id}', [NoteController::class, 'deleteNote'])->name('delete');
    Route::get('/deleteNoteConfirm/{id}', [NoteController::class, 'deleteNoteConfirm'])->name('deleteConfirm');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});