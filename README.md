# Verifier

簡潔なシグネチャで条件を指定できるバリデーションライブラリ。

## インストール

```shell
composer require haikara/verifier
```

## 基本的な使い方

```PHP
$verifier = new Haikara\Verifier\Verifier\Verifier();

// テストデータはすべてバリデーションを通る値
$post = [
    'customer_name' => '顧客1',
    'customer_code' => 'A0000001',
    'category_id' => '1'
];

// 非空文字
$verifier->case(
    'customer_name', // 項目名
    $post['customer_name'] ?? '', // 検証する値
    Rules::string()->nonEmptyString() // 検証条件
);

// 半角英数字、8文字
$verifier->case(
    'customer_code',
    $post['customer_code'] ?? '',
    Rules::string()->alnum()->length(8)
);

// 半角数字、1以上
$verifier->case(
    'category_id',
    $post['category_id'] ?? null,
    Rules::integer()->min(1)
);

// すべての項目が条件を満たせばtrueが返る
var_dump($verifier->verify());
```

### フィルタリング済みの値の取得

`Integer`、`Double`、`Number`による検証は内部で型変換がおこなわれる。  
型変換された後の値を取得したい場合、下記のように記述する。

```PHP
$post = [
    'year' => '2023'
    'month' => '13',
];

// 1994以上の整数
$verifier->case(
    'year',
    $post['year'] ?? null,
    Rules::integer()->min(1994)
);

// (int)2023が返る
$year = $verifier->getCase('year')->getValue();

// 1から12
$monthCase = $verifier->case(
    'month',
    $post['month'] ?? null,
    Rules::integer()->range(1, 12)
);

// 条件を満たさないのでfalseが返る
$month = $monthCase->getValue();
```

### 特定の値を許容する

文字列の検証条件を指定しつつ、空文字も許可する例。

```PHP
// 空文字が弾かれる
Rules::string()->alnum();

// 半角英数字、もしくは空文を許可したい場合
Rules::string()->alnum()->allowEmptyString();

// こちらでも可
Rules::string()->alnum()->allow('');
```

整数値の検証条件を指定しつつ、`null`も許可する例。

```PHP
// 1以上の整数、もしくはnull
Rules::integer()->min(1)->nullable();

// こちらでも可
Rules::integer()->min(1)->allow(null);
```

`alnum`や`length`などの条件は記述した順に判定されるが、  
`allowEmptyString`や`nullable`は各種条件より優先して判定される。

### カスタムバリデーション

`add`で任意の条件を追加する。

```PHP
// メールアドレス、ただし特定のドメインは許可しない。
Rules::string()->email()
    ->add(
        'not_example.com', // 検証条件名。無理矢理にでも何か指定する。
        fn ($value) => !str_ends_with($value, '@example.com') // 検証関数。検証する値を受け取り、bool値を返すことが必要。
    );
```

## エラーメッセージ

検証項目にエラーメッセージを登録することができる。

```PHP
$verifier
    ->case(
        'customer_name',
        $post['customer_name'] ?? '',
        Rules::string()->nonEmptyString()
    )
    ->setMessage('名前を入力ください。');

// 半角英数字、8文字
$verifier
    ->case(
        'customer_code',
        $post['customer_code'] ?? '',
        Rules::string()->alnum()->length(8)
    )
    ->setmessage('顧客コードを半角英数字8文字で入力ください。');

// 半角数字、1以上
$verifier
    ->case(
        'category_id',
        $post['category_id'] ?? null,
        Rules::integer()->min(1)
    )
    ->setMessage('カテゴリを選択ください。');

if (!$verifier->verify()) {
    // 検証で弾かれた項目のエラーメッセージの配列を取得
    $error_messages = $verifier->getMessages();
}
```

## 文字列の検証

```php
// 基本。空文字も含む全ての文字列を許可する。
Rules::string();

// 空文字を弾く場合。
Rules::integer()->notEmptyString();
```

### 文字数

```php
// 文字数を検証する。mb_strlenで判定しているのでマルチバイト文字も可。
Rules::string()->length(8);
```
### 文字列検索

```php
// 部分一致
Rules::string()->contain('探したい文字列');

// 前方一致
Rules::string()->startsWith('探したい文字列');

// 後方一致
Rules::string()->startsWith('探したい文字列');
```

### 文字種

文字列に含まれる文字種の制限。

```php
// 半角英数字のみ
Rules::string()->alnum();

// 半角英字のみ
Rules::string()->alpha();

// 半角数字のみ
Rules::string()->digit();
```

### メールアドレス

メールアドレス形式の文字列かどうかの検証。

```php
Rules::string()->email();
```

### UUID

UUID形式の文字列かどうかの検証。

```php
Rules::string()->uuid();

// UUIDv4形式のみ
Rules::string()->uuid(4);
```

### 正規表現

```php
Rules::string()->pregMatch('/^探したい文字列/');
```

## 数値の検証

- `Rules::integer`
- `Rules::float`
- `Rules::number`

#### 共通の使い方

```php
// 最低値の指定
Rules::integer()->min(1);

// 最大値の指定
Rules::integer()->min(1);

// 最低値と最大値の範囲指定
Rules::integer()->range(1, 12);
```

#### Rules::integer()

```php
// 桁数の指定
Rules::integer()->length(4); // 4桁の整数のみ
```

## ファイル名

```php
// 拡張子の指定
Rules::file()->ext('.jpg', '.png', '.gif');
```

Rules::string()の拡張なので、以下のようにも記述できる

```php
// thumb_で始まる、JPEGかPNGかGIFのファイル名
Rules::file()
    ->startsWith('thumb_')
    ->ext('.jpg', '.png', '.gif');
```
