<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use Inertia\Inertia;

// 入力画面（クエリパラメータを受け取る）
Route::get('/', [BudgetController::class, 'index'])->name('budget.index');

// 計算実行
Route::post('/calculate', [BudgetController::class, 'calculate'])->name('calculate');

// 共有用ページ
Route::get('/share', function () {
    return Inertia::render('Share', [
        'expenses' => request('expenses'),
        'method' => request('method'),
        'ratioA' => request('ratioA'),
        'ratioB' => request('ratioB'),
        'splitA' => request('splitA'),
        'splitB' => request('splitB'),
    ]);
})->name('share');