<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'main'])->name('main');
Route::get('about', [PageController::class, 'about'])->name('about');
Route::get('services', [PageController::class, 'services'])->name('services');
Route::get('servicesDetail', fn () => redirect()->route('services', [], 301))->name('servicesDetail');
Route::get('servicesDetail/{post}', fn (Post $post) => redirect()->route('posts.show', $post, 301))
    ->whereNumber('post')
    ->name('servicesDetail.show');
Route::get('contact', [ContactController::class, 'create'])->name('contact');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

Route::get('lang/{locale}', [LocalizationController::class, 'change'])->name('locale.change');

// Ochiq sahifalar: barcha tashrifchilar postlarni ko‘rishi mumkin.
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show')->whereNumber('post');

// Autentifikatsiya
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'register_store'])->name('register_store');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    // Izoh qoldirish login qilgan foydalanuvchilarga ruxsat.
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
});

// Admin panel. Admin, editor va blogger panelga kira oladi.
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::middleware('staff')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('posts', [AdminController::class, 'posts'])->name('posts.index');
        Route::get('posts/create', [AdminController::class, 'createPost'])->name('posts.create');
        Route::post('posts', [AdminController::class, 'storePost'])->name('posts.store');
        Route::get('posts/{post}/edit', [AdminController::class, 'editPost'])->name('posts.edit');
        Route::put('posts/{post}', [AdminController::class, 'updatePost'])->name('posts.update');
        Route::delete('posts/{post}', [AdminController::class, 'destroyPost'])->name('posts.destroy');
    });

    Route::middleware('admin')->group(function () {
        Route::get('users', [AdminController::class, 'users'])->name('users.index');
        Route::put('users/{user}/roles', [AdminController::class, 'updateUserRole'])->name('users.roles');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        Route::get('comments', [AdminController::class, 'comments'])->name('comments.index');
        Route::delete('comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');

        Route::get('contacts', [AdminController::class, 'contacts'])->name('contacts.index');
        Route::get('contacts/{contactMessage}', [AdminController::class, 'showContact'])->name('contacts.show');
        Route::delete('contacts/{contactMessage}', [AdminController::class, 'destroyContact'])->name('contacts.destroy');

        Route::get('categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('tags', [AdminController::class, 'tags'])->name('tags.index');
        Route::post('tags', [AdminController::class, 'storeTag'])->name('tags.store');
        Route::put('tags/{tag}', [AdminController::class, 'updateTag'])->name('tags.update');
        Route::delete('tags/{tag}', [AdminController::class, 'destroyTag'])->name('tags.destroy');
    });
});
