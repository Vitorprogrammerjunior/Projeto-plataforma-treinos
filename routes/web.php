<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyWorkoutController;
use App\Http\Controllers\MyMealPlanController;
use App\Http\Controllers\MyVideoController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WorkoutController;
use App\Http\Controllers\Admin\MealPlanController;
use App\Http\Controllers\Admin\ClientVideoController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\TabController;
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

// Callbacks de pagamento do registro (público - usuário não está logado ainda)
Route::get('/registro/pagamento/demo/{pendingRegistration}', [PaymentController::class, 'registerDemo'])->name('payment.register.demo');
Route::post('/registro/pagamento/demo/{pendingRegistration}/confirmar', [PaymentController::class, 'confirmRegisterDemo'])->name('payment.register.demo.confirm');
Route::get('/registro/pagamento/sucesso', [PaymentController::class, 'registerSuccess'])->name('payment.register.success');
Route::get('/registro/pagamento/falha', [PaymentController::class, 'registerFailure'])->name('payment.register.failure');
Route::get('/registro/pagamento/pendente', [PaymentController::class, 'registerPending'])->name('payment.register.pending');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Usuário)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minhas-assinaturas', [DashboardController::class, 'subscriptions'])->name('subscriptions.index');

    // Meus Treinos (Cliente)
    Route::get('/meus-treinos', [MyWorkoutController::class, 'index'])->name('my.workouts');
    Route::get('/meus-treinos/{id}', [MyWorkoutController::class, 'show'])->name('my.workouts.show');

    // Minha Dieta (Cliente)
    Route::get('/minha-dieta', [MyMealPlanController::class, 'index'])->name('my.meal-plan');
    Route::get('/minha-dieta/{id}', [MyMealPlanController::class, 'show'])->name('my.meal-plan.show');

    // Meus Vídeos (Cliente)
    Route::get('/meus-videos', [MyVideoController::class, 'index'])->name('my.videos');
    Route::get('/meus-videos/{id}', [MyVideoController::class, 'show'])->name('my.videos.show');

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

    // Gerenciar Clientes
    Route::get('/clientes', [ClientController::class, 'index'])->name('clients');
    Route::get('/clientes/{client}', [ClientController::class, 'show'])->name('clients.show');
    
    // Treinos do Cliente
    Route::get('/clientes/{client}/treinos', [ClientController::class, 'workouts'])->name('clients.workouts');
    Route::post('/clientes/{client}/treinos', [WorkoutController::class, 'store'])->name('clients.workouts.store');
    Route::put('/clientes/{client}/treinos/{workout}', [WorkoutController::class, 'update'])->name('clients.workouts.update');
    Route::delete('/clientes/{client}/treinos/{workout}', [WorkoutController::class, 'destroy'])->name('clients.workouts.destroy');
    
    // Exercícios do Treino
    Route::post('/clientes/{client}/treinos/{workout}/exercicios', [WorkoutController::class, 'addExercise'])->name('clients.workouts.exercises.store');
    Route::put('/clientes/{client}/treinos/{workout}/exercicios/{exercise}', [WorkoutController::class, 'updateExercise'])->name('clients.workouts.exercises.update');
    Route::delete('/clientes/{client}/treinos/{workout}/exercicios/{exercise}', [WorkoutController::class, 'destroyExercise'])->name('clients.workouts.exercises.destroy');
    
    // Plano Alimentar do Cliente
    Route::get('/clientes/{client}/dieta', [ClientController::class, 'mealPlans'])->name('clients.meal-plans');
    Route::post('/clientes/{client}/dieta', [MealPlanController::class, 'store'])->name('clients.meal-plans.store');
    Route::put('/clientes/{client}/dieta/{mealPlan}', [MealPlanController::class, 'update'])->name('clients.meal-plans.update');
    Route::delete('/clientes/{client}/dieta/{mealPlan}', [MealPlanController::class, 'destroy'])->name('clients.meal-plans.destroy');
    
    // Itens da Refeição
    Route::post('/clientes/{client}/dieta/{mealPlan}/refeicao/{meal}/item', [MealPlanController::class, 'addItem'])->name('clients.meal-plans.items.store');
    Route::put('/clientes/{client}/dieta/{mealPlan}/refeicao/{meal}/item/{item}', [MealPlanController::class, 'updateItem'])->name('clients.meal-plans.items.update');
    Route::delete('/clientes/{client}/dieta/{mealPlan}/refeicao/{meal}/item/{item}', [MealPlanController::class, 'destroyItem'])->name('clients.meal-plans.items.destroy');
    
    // Vídeos do Cliente
    Route::get('/clientes/{client}/videos', [ClientController::class, 'videos'])->name('clients.videos');
    Route::post('/clientes/{client}/videos', [ClientVideoController::class, 'store'])->name('clients.videos.store');
    Route::put('/clientes/{client}/videos/{video}', [ClientVideoController::class, 'update'])->name('clients.videos.update');
    Route::delete('/clientes/{client}/videos/{video}', [ClientVideoController::class, 'destroy'])->name('clients.videos.destroy');

    // CRUD Vídeos (antigo - manter para compatibilidade)
    Route::resource('videos', VideoController::class)->except(['show']);
    Route::post('videos/reorder', [VideoController::class, 'reorder'])->name('videos.reorder');

    // CRUD Planos
    Route::resource('plans', AdminPlanController::class)->except(['show']);
    Route::post('plans/{plan}/toggle-active', [AdminPlanController::class, 'toggleActive'])->name('plans.toggle-active');

    // CRUD Abas (organização de vídeos)
    Route::resource('tabs', TabController::class)->except(['show']);
    Route::post('tabs/reorder', [TabController::class, 'reorder'])->name('tabs.reorder');
    Route::post('tabs/{tab}/toggle-active', [TabController::class, 'toggleActive'])->name('tabs.toggle-active');

    // Usuários
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/grant-access', [UserController::class, 'grantAccess'])->name('users.grant-access');
    Route::post('users/{user}/revoke-access', [UserController::class, 'revokeAccess'])->name('users.revoke-access');
});

require __DIR__.'/auth.php';
