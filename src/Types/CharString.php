<?php

declare(strict_types=1);

namespace Haikara\Verifier\Types;

use Haikara\Verifier\Rule;

/**
 * 文字列のルール
 */
class CharString extends Rule
{
    public function __construct()
    {
        $this->add('type', function ($value): bool {
            return is_string($value);
        });
    }

    /**
     * 型変換
     *
     * @param mixed $value
     * @return string|false
     */
    public static function filter(mixed $value): string|bool
    {
        return filter_var($value);
    }

    /**
     * 空文字を許可する
     * @return $this
     */
    public function allowEmptyString(): static
    {
        $this->allow('');
        return $this;
    }

    /**
     * 空文字を許可する
     * @return $this
     */
    public function nonEmptyString(): static
    {
        $this->add(__FUNCTION__, function (string $value): bool {
            return $value !== '';
        });
        return $this;
    }

    /**
     * 文字数を検証。mb_strlenで判定しているのでマルチバイト文字も可。
     *
     * @param int $length
     * @return static
     */
    public function length(int $length): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($length): bool {
            return mb_strlen($value) === $length;
        });
        return $this;
    }

    /**
     * 指定した前置文字で始まるか検証
     * 複数指定した場合、いずれかに合致すればtrueとなる
     * @param string ...$prefixes
     * @return $this
     */
    public function startsWith(string ...$prefixes): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($prefixes): bool {
            foreach ($prefixes as $prefix) {
                // str_starts_withs
                if ($prefix !== '' && mb_strpos($value, $prefix) !== false) {
                    return true;
                }
            }

            return false;
        });

        return $this;
    }

    /**
     * 指定した前置文字で終わるか検証
     * 複数指定した場合、いずれかに合致すればtrueとなる
     * @param string ...$backwards
     * @return $this
     */
    public function endsWith(string ...$backwards): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($backwards): bool {
            foreach ($backwards as $backward) {
                // str_ends_withs
                $backward_len = strlen($backward);
                if (0 === substr_compare($value, $backward, -$backward_len)) {
                    return true;
                }
            }

            return false;
        });
        return $this;
    }

    /**
     * 指定した前置文字を含むか検証
     * 複数指定した場合、いずれかに合致すればtrueとなる
     * @param string ...$partials
     * @return $this
     */
    public function contain(string ...$partials): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($partials): bool {
            foreach ($partials as $partial) {
                // str_contains
                if (mb_strpos($value, $partial) !== false) {
                    return true;
                }
            }

            return false;
        });
        return $this;
    }

    /**
     * @return $this
     */
    public function alnum(): static
    {
        $this->add(__FUNCTION__, 'ctype_alnum');
        return $this;
    }

    /**
     * @return $this
     */
    public function alpha(): static
    {
        $this->add(__FUNCTION__, 'ctype_alpha');
        return $this;
    }

    /**
     * @return $this
     */
    public function digit(): static
    {
        $this->add(__FUNCTION__, 'ctype_digit');
        return $this;
    }

    /**
     * @param int|null $version
     * @return static
     */
    public function uuid(?int $version = null): static
    {
        $version_str = (is_int($version) && strlen((string)$version) === 1) ? $version : '0-7';
        $pattern = "/\A[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[{$version_str}][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}\z/";

        $this->add(__FUNCTION__, function (string $value) use ($pattern): bool {
            return (bool)preg_match($pattern, $value);
        });
        return $this;
    }

    /**
     * @param string $pattern
     * @return static
     */
    public function pregMatch(string $pattern): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($pattern): bool {
            return (bool)preg_match($pattern, $value);
        });
        return $this;
    }

    /**
     * @return static
     */
    public function email(): static
    {
        $this->add(__FUNCTION__, function (string $value): bool {
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        });
        return $this;
    }
}
