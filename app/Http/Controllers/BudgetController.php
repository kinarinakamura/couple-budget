<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BudgetController extends Controller
{
    /**
     * 入力画面を表示
     */
    public function index(Request $request)
    {
        return Inertia::render('Input', [
            'previousInput' => [
                'expenses' => $request->query('expenses'),
                'splitMethod' => $request->query('splitMethod'),
                'incomeA' => $request->query('incomeA'),
                'incomeB' => $request->query('incomeB'),
                'ratioA' => $request->query('ratioA'),
                'ratioB' => $request->query('ratioB'),
            ],
        ]);
    }

    /**
     * 計算を実行
     */
    public function calculate(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'expenses' => 'required|numeric|min:0.1',
            'splitMethod' => 'required|in:income,equal,custom',
            'incomeA' => 'nullable|numeric|min:1',
            'incomeB' => 'nullable|numeric|min:1',
            'ratioA' => 'nullable|numeric|min:0|max:100',
            'ratioB' => 'nullable|numeric|min:0|max:100',
        ], [
            'expenses.required' => '生活費を入力してください',
            'expenses.min' => '生活費は0.1万円以上で入力してください',
            'incomeA.min' => '年収は1万円以上で入力してください',
            'incomeB.min' => '年収は1万円以上で入力してください',
        ]);

        $expenses = $validated['expenses'];
        $method = $validated['splitMethod'];

        // 分担方法に応じて計算
        $result = match($method) {
            'income' => $this->calculateByIncome(
                $validated['incomeA'],
                $validated['incomeB'],
                $expenses
            ),
            'equal' => $this->calculateEqual($expenses),
            'custom' => $this->calculateByRatio(
                $validated['ratioA'],
                $validated['ratioB'],
                $expenses
            ),
        };

        // 結果画面に遷移（入力値も一緒に渡す）
        return Inertia::render('Result', [
            'result' => $result,
            'method' => $method,
            'expenses' => $expenses,
            'inputData' => $validated,
        ]);
    }

    /**
     * 年収に応じた手取り率を計算（万円単位）
     */
    // private function calculateTakeHomeRate(float $annualIncome): float
    // {
    //     if ($annualIncome <= 200) {
    //         return 0.82;  // 低所得 → 控除少ない
    //     } elseif ($annualIncome <= 300) {
    //         return 0.80;
    //     } elseif ($annualIncome <= 400) {
    //         return 0.78;
    //     } elseif ($annualIncome <= 500) {
    //         return 0.77;
    //     } elseif ($annualIncome <= 600) {
    //         return 0.76;
    //     } elseif ($annualIncome <= 800) {
    //         return 0.75;
    //     } elseif ($annualIncome <= 1000) {
    //         return 0.73;
    //     } elseif ($annualIncome <= 1500) {
    //         return 0.71;
    //     } else {
    //         return 0.68;  // 高所得 → 税率高い
    //     }
    // }

    /**
     * 収入比例で計算
     */
    private function calculateByIncome($incomeA, $incomeB, $expenses)
    {
        $total = $incomeA + $incomeB;
        $ratioA = $total > 0 ? $incomeA / $total : 0.5;
        $ratioB = 1 - $ratioA;

        $splitA = $expenses * $ratioA;
        $splitB = $expenses * $ratioB;

        // 手取りに対する負担率（年収に応じた手取り率で計算）
        // $takeHomeA = $incomeA * $this->calculateTakeHomeRate($incomeA);
        // $takeHomeB = $incomeB * $this->calculateTakeHomeRate($incomeB);

        $takeHomeA = $incomeA * 0.8;
        $takeHomeB = $incomeB * 0.8;

        $burdenRateA = $takeHomeA > 0 ? ($splitA * 12 / $takeHomeA) * 100 : 0;
        $burdenRateB = $takeHomeB > 0 ? ($splitB * 12 / $takeHomeB) * 100 : 0;

        return [
            'splitA' => round($splitA, 1),
            'splitB' => round($splitB, 1),
            'ratioA' => round($ratioA * 100, 1),
            'ratioB' => round($ratioB * 100, 1),
            'incomeA' => $incomeA,
            'incomeB' => $incomeB,
            'burdenRateA' => round($burdenRateA, 1),
            'burdenRateB' => round($burdenRateB, 1),
        ];
    }

    /**
     * 均等分担で計算
     */
    private function calculateEqual($expenses)
    {
        $split = $expenses / 2;

        return [
            'splitA' => round($split, 1),
            'splitB' => round($split, 1),
            'ratioA' => 50,
            'ratioB' => 50,
        ];
    }

    /**
     * カスタム比率で計算
     */
    private function calculateByRatio($ratioA, $ratioB, $expenses)
    {
        $total = $ratioA + $ratioB;
        $normalizedA = $total > 0 ? $ratioA / $total : 0.5;
        $normalizedB = 1 - $normalizedA;

        return [
            'splitA' => round($expenses * $normalizedA, 1),
            'splitB' => round($expenses * $normalizedB, 1),
            'ratioA' => round($normalizedA * 100, 1),
            'ratioB' => round($normalizedB * 100, 1),
        ];
    }
}