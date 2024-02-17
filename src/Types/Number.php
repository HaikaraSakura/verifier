<?php

declare(strict_types=1);

namespace Haikara\Verifier\Types;

use Haikara\Verifier\Rule;

/**
 * 整数か浮動小数点数のルール
 */
class Number extends Rule
{
    public function __construct()
    {
        $this->add('type', function ($value): bool {
            return is_int($value) || is_float($value);
        });
    }

    /**
     * 型変換
     *
     * @param mixed $value
     * @return int|float|false
     */
    public static function filter(mixed $value): int|float|bool
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if (is_int($integer)) {
            return $integer;
        }

        $float = filter_var($value, FILTER_VALIDATE_FLOAT);

        if (is_float($float)) {
            return $float;
        }

        return false;
    }

    /**
     * 値が一定以上であることを検証
     *
     * @param int|float $min
     * @return static
     */
    public function min(int|float $min): static
    {
        $this->add(__FUNCTION__, function ($value) use ($min) : bool {
            return $value >= $min;
        });
        return $this;
    }

    /**
     * 値が一定以下であることを検証
     *
     * @param int|float $max
     * @return static
     */
    public function max(int|float $max): static
    {
        $this->add(__FUNCTION__, function ($value) use ($max) : bool {
            return $value <= $max;
        });
        return $this;
    }

    /**
     * 値が範囲内であることを検証
     *
     * @param int|float $min
     * @param int|float $max
     * @return static
     */
    public function range(int|float $min, int|float $max): static
    {
        $this->add(__FUNCTION__, function ($value) use ($min, $max) : bool {
            return $min <= $value && $value <= $max;
        });
        return $this;
    }
}
