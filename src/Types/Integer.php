<?php

declare(strict_types=1);

namespace Haikara\Verifier\Types;

use Haikara\Verifier\Rule;

use function filter_var;
use function is_int;
use function strlen;

/**
 * 整数のルール
 */
class Integer extends Rule
{
    public function __construct()
    {
        $this->add('type', function ($value): bool {
            return is_int($value);
        });
    }

    /**
     * 型変換
     *
     * @param mixed $value
     * @return int|false
     */
    public static function filter(mixed $value): int|bool
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * 値が一定以上であることを検証
     *
     * @param int $min
     * @return $this
     */
    public function min(int $min): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($min) : bool {
            return $value >= $min;
        });
        return $this;
    }

    /**
     * 値が一定以下であることを検証
     *
     * @param int $max
     * @return $this
     */
    public function max(int $max): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($max) : bool {
            return $value <= $max;
        });
        return $this;
    }

    /**
     * 値が範囲内であることを検証
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function range(int $min, int $max): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($min, $max) : bool {
            return $min <= $value && $value <= $max;
        });
        return $this;
    }

    /**
     * 値が偶数であることを検証
     *
     * @return $this
     */
    public function even(): static
    {
        $this->add(__FUNCTION__, function (int $value): bool {
            return $value % 2 === 0;
        });
        return $this;
    }

    /**
     * 値が奇数であることを検証
     *
     * @return $this
     */
    public function odd(): static
    {
        $this->add(__FUNCTION__, function (int $value): bool {
            return $value % 2 !== 0;
        });
        return $this;
    }

    /**
     * 値が特定の値の倍数であることを検証
     *
     * @param int $multiple
     * @return $this
     */
    public function multipleOf(int $multiple): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($multiple) : bool {
            return $value % $multiple === 0;
        });
        return $this;
    }

    /**
     * 値が月であることを検証
     *
     * @return $this
     */
    public function month(): static
    {
        $this->add(__FUNCTION__, function (int $value) : bool {
            return 1 <= $value && $value <= 12;
        });
        return $this;
    }

    /**
     * 値が日であることを検証
     *
     * @return $this
     */
    public function day(): static
    {
        $this->add(__FUNCTION__, function (int $value) : bool {
            return 1 <= $value && $value <= 31;
        });
        return $this;
    }

    /**
     * 値が時であることを検証
     *
     * @return $this
     */
    public function hour(): static
    {
        $this->add(__FUNCTION__, function (int $value) : bool {
            return 0 <= $value && $value <= 23;
        });
        return $this;
    }

    /**
     * 値が分であることを検証
     *
     * @return $this
     */
    public function minute(): static
    {
        $this->add(__FUNCTION__, function (int $value) : bool {
            return 0 <= $value && $value <= 59;
        });
        return $this;
    }

    /**
     * 値が秒であることを検証
     *
     * @return $this
     */
    public function second(): static
    {
        $this->add(__FUNCTION__, function (int $value) : bool {
            return 0 <= $value && $value <= 59;
        });
        return $this;
    }

    /**
     * 桁数を検証
     *
     * @param int $length
     * @return $this
     */
    public function length(int $length): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($length) : bool {
            return strlen((string)$value) === $length;
        });
        return $this;
    }
}
