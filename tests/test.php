<?php

declare(strict_types=1);

use Haikara\Verifier\Rules;
use Haikara\Verifier\Verifier;

require __DIR__ . '/../vendor/autoload.php';

$post = [
    'name' => '氏名',
    'customer_code' => '顧客番号',
    'category_id' => '1',
    'content' => [1,2,3]
];

$verifier = new Verifier();

// 非空文字
$verifier->case(
    'name',
    mb_convert_kana(filter_var($post['name'] ?? ''), 'as'),
    Rules::string()->nonEmptyString()
);

// 半角英数字
$verifier->case(
    'customer_code',
    trim(mb_convert_kana(filter_var($post['customer_code'] ?? ''), 'as')),
    Rules::string()->alnum()
);

// 半角数字
$verifier->case(
    'category_id',
    $post['category_id'] ?? null,
    Rules::integer()
);

// あらゆる文字列
$verifier->case(
    'content',
    (static function ($moke) {
        return is_string($moke) ? trim(mb_convert_kana($moke, 'as')) : '';
    })($post['content'] ?? ''),
    Rules::string()
);

foreach ($verifier->getCases() as $name => $case) {
    var_dump($case->isValid());
}
