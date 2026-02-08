<?php

namespace Tests\Unit;

use App\Http\Controllers\BudgetController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class TakeHomeRateTest extends TestCase
{
    private BudgetController $controller;
    private ReflectionMethod $method;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BudgetController();
        $this->method = new ReflectionMethod(BudgetController::class, 'calculateTakeHomeRate');
        $this->method->setAccessible(true);
    }

    /**
     * 年収200万円以下は82%
     */
    public function test_income_up_to_200(): void
    {
        $this->assertEquals(0.82, $this->method->invoke($this->controller, 100));
        $this->assertEquals(0.82, $this->method->invoke($this->controller, 200));
    }

    /**
     * 年収201〜300万円は80%
     */
    public function test_income_201_to_300(): void
    {
        $this->assertEquals(0.80, $this->method->invoke($this->controller, 201));
        $this->assertEquals(0.80, $this->method->invoke($this->controller, 300));
    }

    /**
     * 年収301〜400万円は78%
     */
    public function test_income_301_to_400(): void
    {
        $this->assertEquals(0.78, $this->method->invoke($this->controller, 301));
        $this->assertEquals(0.78, $this->method->invoke($this->controller, 400));
    }

    /**
     * 年収401〜500万円は77%
     */
    public function test_income_401_to_500(): void
    {
        $this->assertEquals(0.77, $this->method->invoke($this->controller, 401));
        $this->assertEquals(0.77, $this->method->invoke($this->controller, 500));
    }

    /**
     * 年収501〜600万円は76%
     */
    public function test_income_501_to_600(): void
    {
        $this->assertEquals(0.76, $this->method->invoke($this->controller, 501));
        $this->assertEquals(0.76, $this->method->invoke($this->controller, 600));
    }

    /**
     * 年収601〜800万円は75%
     */
    public function test_income_601_to_800(): void
    {
        $this->assertEquals(0.75, $this->method->invoke($this->controller, 601));
        $this->assertEquals(0.75, $this->method->invoke($this->controller, 800));
    }

    /**
     * 年収801〜1000万円は73%
     */
    public function test_income_801_to_1000(): void
    {
        $this->assertEquals(0.73, $this->method->invoke($this->controller, 801));
        $this->assertEquals(0.73, $this->method->invoke($this->controller, 1000));
    }

    /**
     * 年収1001〜1500万円は71%
     */
    public function test_income_1001_to_1500(): void
    {
        $this->assertEquals(0.71, $this->method->invoke($this->controller, 1001));
        $this->assertEquals(0.71, $this->method->invoke($this->controller, 1500));
    }

    /**
     * 年収1500万円超は68%
     */
    public function test_income_over_1500(): void
    {
        $this->assertEquals(0.68, $this->method->invoke($this->controller, 1501));
        $this->assertEquals(0.68, $this->method->invoke($this->controller, 2000));
        $this->assertEquals(0.68, $this->method->invoke($this->controller, 5000));
    }

    /**
     * 境界値テスト
     */
    public function test_boundary_values(): void
    {
        // 各境界値での正確な判定を確認
        $this->assertEquals(0.82, $this->method->invoke($this->controller, 200));
        $this->assertEquals(0.80, $this->method->invoke($this->controller, 200.01));

        $this->assertEquals(0.80, $this->method->invoke($this->controller, 300));
        $this->assertEquals(0.78, $this->method->invoke($this->controller, 300.01));

        $this->assertEquals(0.71, $this->method->invoke($this->controller, 1500));
        $this->assertEquals(0.68, $this->method->invoke($this->controller, 1500.01));
    }
}