<?php

declare(strict_types=1);

namespace Haikara\Verifier\Types;

use Haikara\Verifier\Rule;

/**
 * 浮動小数点数のルール
 */
class Double extends Rule
{
    public function __construct()
    {
        $this->add('type', function ($value): bool {
            return is_float($value);
        });
    }

    /**
     * 型変換
     *
     * @param mixed $value
     * @return float|false
     */
    public static function filter(mixed $value): float|bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    /**
     * 値が一定以上であることを検証
     *
     * @param float|int $min
     * @return static
     */
    public function min(float|int $min): static
    {
        $this->add(__FUNCTION__, function (float $value) use ($min) : bool {
            return $value >= $min;
        });
        return $this;
    }

    /**
     * 値が一定以下であることを検証
     *
     * @param float|int $max
     * @return static
     */
    public function max(float|int $max): static
    {
        $this->add(__FUNCTION__, function (float $value) use ($max) : bool {
            return $value <= $max;
        });
        return $this;
    }

    /**
     * 値が範囲内であることを検証
     *
     * @param float|int $min
     * @param float|int $max
     * @return static
     */
    public function range(float|int $min, float|int $max): static
    {
        $this->add(__FUNCTION__, function (float $value) use ($min, $max) : bool {
            return $min <= $value && $value <= $max;
        });
        return $this;
    }
}
