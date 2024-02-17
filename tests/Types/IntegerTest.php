<?php

declare(strict_types=1);

namespace Types;

use Haikara\Verifier\Types\Integer;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    public function test_filter() {
        $rule = new Integer();

        // 整数を渡すと同じ整数が返る
        $this->assertSame($rule::filter(1), 1);

        // 整数とみなせる文字列を渡すと、整数にキャストした値が返る
        $this->assertSame($rule::filter('1'), 1);

        // 整数、もしくは整数とみなせる文字列ではない場合、falseが返る
        $this->assertFalse($rule::filter(null));
        $this->assertFalse($rule::filter('a'));
    }

    public function test_min() {
        $rule = (new Integer())->min(1);

        // 一致
        $this->assertTrue($rule->match(100));
        $this->assertTrue($rule->match(1));

        // 不一致
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(-1));
    }

    public function test_max() {
        $rule = (new Integer())->max(100);

        // 一致
        $this->assertTrue($rule->match(100));
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(-1));

        // 不一致
        $this->assertFalse($rule->match(101));
    }

    public function test_range() {
        $rule = (new Integer())->range(1, 100);

        // 一致
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(100));
        $this->assertTrue($rule->match(50));

        // 不一致
        $this->assertFalse($rule->match(-1));
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(101));
    }

    public function test_even() {
        $rule = (new Integer())->even();

        // 一致
        $this->assertTrue($rule->match(2));
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(-2));

        // 不一致
        $this->assertFalse($rule->match(101));
        $this->assertFalse($rule->match(-101));
    }

    public function test_odd() {
        $rule = (new Integer())->odd();

        // 一致
        $this->assertTrue($rule->match(101));
        $this->assertTrue($rule->match(-101));

        // 不一致
        $this->assertFalse($rule->match(2));
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(-2));
    }

    public function test_multipleOf() {
        $rule = (new Integer())->multipleOf(3);

        // 一致
        $this->assertTrue($rule->match(3));
        $this->assertTrue($rule->match(60));
        $this->assertTrue($rule->match(-60));

        // 不一致
        $this->assertFalse($rule->match(100));
    }

    public function test_month() {
        $rule = (new Integer())->month();

        // 一致
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(6));
        $this->assertTrue($rule->match(12));

        // 不一致
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(13));
    }

    public function test_day() {
        $rule = (new Integer())->day();

        // 一致
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(15));
        $this->assertTrue($rule->match(31));

        // 不一致
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(32));
    }

    public function test_hour() {
        $rule = (new Integer())->hour();

        // 一致
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(12));
        $this->assertTrue($rule->match(23));

        // 不一致
        $this->assertFalse($rule->match(-1));
        $this->assertFalse($rule->match(24));
    }

    public function test_minute() {
        $rule = (new Integer())->minute();

        // 一致
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(30));
        $this->assertTrue($rule->match(59));

        // 不一致
        $this->assertFalse($rule->match(-1));
        $this->assertFalse($rule->match(60));
    }

    public function test_second() {
        $rule = (new Integer())->second();

        // 一致
        $this->assertTrue($rule->match(0));
        $this->assertTrue($rule->match(30));
        $this->assertTrue($rule->match(59));

        // 不一致
        $this->assertFalse($rule->match(-1));
        $this->assertFalse($rule->match(60));
    }

    public function test_length() {
        $rule = (new Integer())->length(3);

        // 一致
        $this->assertTrue($rule->match(100));

        // 不一致
        $this->assertFalse($rule->match(3));
        $this->assertFalse($rule->match(21));
    }

    public function test_in() {
        $rule = (new Integer())->in([1, 2, 3]);

        // 一致
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(2));
        $this->assertTrue($rule->match(3));

        // 不一致
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(4));
    }

    public function test_nullable() {
        $rule = (new Integer())->min(1)->nullable();

        // 一致
        $this->assertTrue($rule->match(100));
        $this->assertTrue($rule->match(null));

        // 不一致
        $this->assertFalse($rule->match(0));
    }

    /**
     * 複数の条件を指定した場合のテスト
     * @return void
     */
    public function test_multiRule() {
        $rule = (new Integer())->min(1)->max(12);

        // 一致
        $this->assertTrue($rule->match(1));
        $this->assertTrue($rule->match(12));
        $this->assertTrue($rule->match(6));

        // 不一致
        $this->assertFalse($rule->match(-1));
        $this->assertFalse($rule->match(0));
        $this->assertFalse($rule->match(13));
    }
}
