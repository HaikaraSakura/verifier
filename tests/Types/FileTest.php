<?php

declare(strict_types=1);

namespace Types;

use Haikara\Verifier\Types\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function test_filter() {
        $rule = new File();

        // 整数もしくは浮動小数点数を渡すと、値がそのまま返る
        $this->assertSame($rule::filter('sample.txt'), 'sample.txt');
        $this->assertSame($rule::filter('/sample.txt'), 'sample.txt');
        $this->assertSame($rule::filter(null), '');
        $this->assertSame($rule::filter(false), '');
        $this->assertSame($rule::filter(true), '1');

        // 配列を渡すとfalseが返る
        $this->assertFalse($rule::filter([1, 2, 3]));
    }
    public function test_ext() {
        $rule = (new File())->ext('txt');

        // 一致
        $this->assertTrue($rule->match('sample.txt'));

        // 不一致
        $this->assertFalse($rule->match('sample.php'));

        $rule = (new File())->ext('txt', 'php');

        // 一致
        $this->assertTrue($rule->match('sample.txt'));

        // 一致
        $this->assertTrue($rule->match('sample.php'));

        // 不一致
        $this->assertFalse($rule->match('sample.html'));
    }
}
