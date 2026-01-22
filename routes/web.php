<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Planos (público)
Route::get('/planos', [PlanController::class, 'index'])->name('plans.index');
Route::get('/planos/{plan:slug}', [PlanController::class, 'show'])->name('plans.show');

// Webhook de pagamento (público, sem CSRF)
Route::post('/webhook/payment', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware(['web']);

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Usuário)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/meus-videos', [DashboardController::class, 'index'])->name('videos.index');
    Route::get('/minhas-assinaturas', [DashboardController::class, 'subscriptions'])->name('subscriptions.index');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout
    Route::post('/planos/{plan:slug}/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');

    // Callbacks de pagamento
    Route::get('/pagamento/demo/{subscription}', [PaymentController::class, 'demo'])->name('payment.demo');
    Route::post('/pagamento/demo/{subscription}/confirmar', [PaymentController::class, 'confirmDemo'])->name('payment.demo.confirm');
    Route::get('/pagamento/sucesso/{subscription}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/pagamento/cancelado/{subscription}', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // Assistir vídeo (com verificação de acesso no controller)
    Route::get('/assistir/{video:slug}', [DashboardController::class, 'watch'])->name('videos.watch');
    
    // Stream de vídeo protegido
    Route::get('/stream/{video:slug}', [DashboardController::class, 'stream'])->name('videos.stream');
});

/*
|--------------------------------------------------------------------------
| Rotas Administrativas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Vídeos
    Route::resource('videos', VideoController::class)->except(['show']);
    Route::post('videos/reorder', [VideoController::class, 'reorder'])->name('videos.reorder');

    // CRUD Planos
    Route::resource('plans', AdminPlanController::class)->except(['show']);
    Route::post('plans/{plan}/toggle-active', [AdminPlanController::class, 'toggleActive'])->name('plans.toggle-active');

    // Usuários
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/grant-access', [UserController::class, 'grantAccess'])->name('users.grant-access');
    Route::post('users/{user}/revoke-access', [UserController::class, 'revokeAccess'])->name('users.revoke-access');
});

require __DIR__.'/auth.php';
