<?php

declare(strict_types=1);

namespace Types;

use Haikara\Verifier\Rules;
use Haikara\Verifier\ValidateCase;
use PHPUnit\Framework\TestCase;

class ValidateCaseTest extends TestCase
{
    public function test_success() {
        $case = new ValidateCase(
            'category_id',
            '1',
            Rules::integer()->min(1)
        );

        $message = 'カテゴリを選択ください。';

        $case->setMessage($message);

        // 検証結果
        $this->assertTrue($case->isValid());

        // 項目名の取得
        $this->assertSame($case->getName(), 'category_id');

        // 値の取得
        $this->assertSame($case->getValue(), 1);

        // 値の比較メソッド
        $this->assertTrue($case->equals(1));

        // メッセージの取得
        $this->assertSame($case->getMessage(), $message);
    }

    public function test_failed() {
        $case = new ValidateCase(
            'category_id',
            0,
            Rules::integer()->min(1)
        );

        // 検証結果
        $this->assertFalse($case->isValid());
    }

    public function test_nullable() {
        $case = new ValidateCase(
            'category_id',
            null,
            Rules::integer()->min(1)->nullable()
        );

        // 検証結果
        $this->assertTrue($case->isValid());
        $this->assertNull($case->getValue());
    }

    public function test_getValue() {
        $case = new ValidateCase(
            'category_id',
            1,
            Rules::integer()->min(1)
        );

        // 検証結果
        $this->assertIsInt($case->getValue());

        $case = new ValidateCase(
            'category_id',
            null,
            Rules::integer()->min(1)->nullable()
        );

        // 検証結果
        $this->assertNull($case->getValue());
    }
}
