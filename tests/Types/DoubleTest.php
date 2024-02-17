<?php

declare(strict_types=1);

namespace Types;

use Haikara\Verifier\Types\Double;
use PHPUnit\Framework\TestCase;

class DoubleTest extends TestCase
{
    public function test_filter() {
        $rule = new Double();

        // 浮動小数点数を渡すと、値がそのまま返る
        $this->assertSame($rule::filter(0.1), 0.1);

        // 浮動小数点数の文字列を渡すと、キャストされた値が返る
        $this->assertSame($rule::filter('0.1'), 0.1);

        // 整数を渡すと、キャストされた値が返る
        $this->assertSame($rule::filter(1), 1.0);
        $this->assertSame($rule::filter('1'), 1.0);

        $this->assertFalse($rule::filter(null));
        $this->assertFalse($rule::filter(false));
        $this->assertFalse($rule::filter([1, 2, 3]));
    }

    public function test_min() {
        $rule = (new Double())->min(1);

        // 一致
        $this->assertTrue($rule->match(1.0));
        $this->assertTrue($rule->match(1));

        // 不一致
        $this->assertFalse($rule->match(0.9));
        $this->assertFalse($rule->match('abc'));
    }

    public function test_max() {
        $rule = (new Double())->max(10);

        // 一致
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(10.0));

        // 不一致
        $this->assertFalse($rule->match(10.1));
    }

    public function test_range() {
        $rule = (new Double())->range(1, 10);

        // 一致
        $this->assertTrue($rule->match(1.0));
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(10));
        $this->assertTrue($rule->match(10.0));

        // 不一致
        $this->assertFalse($rule->match(-0.1));
        $this->assertFalse($rule->match(10.1));
    }

    /**
     * 複数の条件を指定した場合のテスト
     * @return void
     */
    public function test_multiRule() {
        $rule = (new Double())->min(1)->max(100);

        // 一致
        $this->assertTrue($rule->match(1.0));
        $this->assertTrue($rule->match(50.0));
        $this->assertTrue($rule->match(100.0));

        // 不一致
        $this->assertFalse($rule->match(0.9));
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(100.1));
    }
}
