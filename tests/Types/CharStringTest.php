<?php

declare(strict_types=1);

namespace Types;

use Haikara\Verifier\Types\CharString;
use PHPUnit\Framework\TestCase;

class CharStringTest extends TestCase
{
    public function test_filter()
    {
        $rule = new CharString();

        // 文字列を渡すとそのままの値が返る
        $this->assertSame($rule::filter('abc'), 'abc');

        // 整数を渡すと、その文字列表現が返る
        $this->assertSame($rule::filter(1), '1');

        $this->assertSame($rule::filter(null), '');
        $this->assertSame($rule::filter(false), '');
        $this->assertSame($rule::filter(true), '1');

        // 配列を渡すとfalseが返る
        $this->assertFalse($rule::filter([1, 2, 3]));
    }

    public function test_allowEmptyString()
    {
        $rule = (new CharString())->digit()->allowEmptyString();

        // 一致
        $this->assertTrue($rule->match('123'));
        $this->assertTrue($rule->match(''));

        // 不一致
        $this->assertFalse($rule->match('abc123'));
        $this->assertFalse($rule->match(' '));
    }

    public function test_startsWith()
    {
        $rule = (new CharString())->startsWith('test');

        // 一致
        $this->assertTrue($rule->match('test'));
        $this->assertTrue($rule->match('test@konishi-p.co.jp'));

        // 不一致
        $this->assertFalse($rule->match('aaa@konishi-p.co.jp'));
        $this->assertFalse($rule->match('tes@konishi-p.co.jp'));
    }

    public function test_startsWithMulti()
    {
        $rule = (new CharString())->startsWith('test', 'sample');

        // 一致
        $this->assertTrue($rule->match('test'));
        $this->assertTrue($rule->match('sample'));
        $this->assertTrue($rule->match('test@konishi-p.co.jp'));
        $this->assertTrue($rule->match('sample@konishi-p.co.jp'));

        // 不一致
        $this->assertFalse($rule->match('aaa@konishi-p.co.jp'));
        $this->assertFalse($rule->match('tes@konishi-p.co.jp'));
    }

    public function test_endsWith()
    {
        $rule = (new CharString())->endsWith('@konishi-p.co.jp');

        // 一致
        $this->assertTrue($rule->match('@konishi-p.co.jp'));
        $this->assertTrue($rule->match('test@konishi-p.co.jp'));

        // 不一致
        $this->assertFalse($rule->match('@gmail.com'));
        $this->assertFalse($rule->match('konishi-p.co.jp'));
    }

    public function test_endsWithMulti()
    {
        $rule = (new CharString())->endsWith('@example.com', '@test.com');

        // 一致
        $this->assertTrue($rule->match('example@example.com'));
        $this->assertTrue($rule->match('test@test.com'));

        // 不一致
        $this->assertFalse($rule->match('sample@example.ne.jp'));
        $this->assertFalse($rule->match('sample@example.co.jp'));
    }

    public function test_contain()
    {
        $rule = (new CharString())->contain('def');

        // 一致
        $this->assertTrue($rule->match('def'));
        $this->assertTrue($rule->match('abcdefghi'));

        // 不一致
        $this->assertFalse($rule->match('deef'));
    }

    public function test_containMulti()
    {
        $rule = (new CharString())->contain('abc', 'def');

        // 一致
        $this->assertTrue($rule->match('abc'));
        $this->assertTrue($rule->match('def'));
        $this->assertTrue($rule->match('abcdefghijklmnopqrstuvwxyz'));

        // 不一致
        $this->assertFalse($rule->match('bcde'));
    }

    public function test_alnum()
    {
        $rule = (new CharString())->alnum();

        // 一致
        $this->assertTrue($rule->match('abc'));
        $this->assertTrue($rule->match('123'));
        $this->assertTrue($rule->match('A1'));

        // 不一致
        $this->assertFalse($rule->match('あ'));
        $this->assertFalse($rule->match('Ａ１'));
        $this->assertFalse($rule->match('A-1'));
    }

    public function test_alpha()
    {
        $rule = (new CharString())->alpha();

        // 一致
        $this->assertTrue($rule->match('abc'));

        // 不一致
        $this->assertFalse($rule->match('あ'));
        $this->assertFalse($rule->match('ＡＢＣ'));
        $this->assertFalse($rule->match('A-B-C'));
    }

    public function test_digit()
    {
        $rule = (new CharString())->digit();

        // 一致
        $this->assertTrue($rule->match('123'));
        $this->assertTrue($rule->match('001'));

        // 不一致
        $this->assertFalse($rule->match('1A'));
        $this->assertFalse($rule->match('１２３'));
    }

    public function test_uuid()
    {
        $rule = (new CharString())->uuid();

        // 一致
        $this->assertTrue($rule->match('d5794b1b-5f92-4dc6-aa48-085dbb08b813'));

        // 不一致
        $this->assertFalse($rule->match('g5794b1b-5f92-4dc6-aa48-085dbb08b813'));

        $rule = (new CharString())->uuid(1);

        // 一致
        $this->assertTrue($rule->match('d5794b1b-5f92-1dc6-aa48-085dbb08b813'));

        // 不一致
        $this->assertFalse($rule->match('d5794b1b-5f92-4dc6-aa48-085dbb08b813'));
    }

    public function test_pregMatch()
    {
        $rule = (new CharString())->pregMatch('/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/');

        // 一致
        $this->assertTrue($rule->match('090-1111-1111'));
        $this->assertTrue($rule->match('078-111-1111'));

        // 不一致
        $this->assertFalse($rule->match('09011111111'));
        $this->assertFalse($rule->match('11-11-111'));
    }

    /**
     * 複数の条件を指定した場合のテスト
     * @return void
     */
    public function test_multiRule()
    {
        $rule = (new CharString())->alnum()->length(5);

        // 一致
        $this->assertTrue($rule->match('12345'));
        $this->assertTrue($rule->match('00001'));
        $this->assertTrue($rule->match('A0001'));

        // 不一致
        $this->assertFalse($rule->match('A001'));
        $this->assertFalse($rule->match('A-001'));
        $this->assertFalse($rule->match('A00001'));
    }

    /**
     * 複数の条件を指定した場合のテスト
     * @return void
     */
    public function test_emailRule()
    {
        $rule = (new CharString())->email();

        // 一致
        $this->assertTrue($rule->match('test@example.com'));
        $this->assertTrue($rule->match('prefix+test@example.com'));

        // 不一致
        $this->assertFalse($rule->match('test$example.com'));
    }
}
