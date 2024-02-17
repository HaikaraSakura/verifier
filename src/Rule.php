<?php

declare(strict_types=1);

namespace Haikara\Verifier;

use Closure;
use Traversable;

abstract class Rule implements RuleInterface
{
    /**
     * 値を検証する処理のキュー
     * @var array<string, Closure>
     */
    protected array $validateFunctions;

    protected array $allowValues = [];

    /**
     * @inheritDoc
     */
    public function nullable(): static {
        $this->allow(null);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function allow(...$values): static {
        $this->allowValues = array_merge($this->allowValues, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(string $rule_name, callable $callback): static
    {
        $callback = $callback instanceof Closure
            ? $callback
            : Closure::fromCallable($callback);

        $this->validateFunctions[$rule_name] = $callback;

        return $this;
    }

    /**
     * 指定のiterable値に含まれるか検証
     *
     * @param iterable $values
     * @return $this
     */
    public function in(iterable $values): static
    {
        $this->add(__FUNCTION__, function (int $value) use ($values) : bool {
            if ($values instanceof Traversable) {
                $values = iterator_to_array($values);
            }

            return in_array($value, $values, true);
        });
        return $this;
    }

    public function filterVar($value): mixed
    {
        // $this->allow_valuesに含まれる値なら検証を実行しない
        if (in_array($value, $this->allowValues, true)) {
            return $value;
        }

        return static::filter($value);
    }

    /**
     * @inheritDoc
     */
    public function match($value): bool
    {
        // $this->allow_valuesに含まれる値なら検証を実行しない
        if (in_array($value, $this->allowValues, true)) {
            return true;
        }

        $value = static::filter($value);

        // 検証関数を順次実行し、合致しなければその時点でreturn
        foreach ($this->validateFunctions as $callback) {
            if (!$callback($value)) {
                return false;
            }
        }

        return true;
    }
}
