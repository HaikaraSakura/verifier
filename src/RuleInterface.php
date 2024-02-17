<?php

declare(strict_types=1);

namespace Haikara\Verifier;

interface RuleInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public static function filter(mixed $value): mixed;

    /**
     * @param string $rule_name
     * @param callable $callback
     * @return self
     */
    public function add(string $rule_name, callable $callback): static;

    /**
     * @param mixed ...$values
     * @return RuleInterface
     */
    public function allow(mixed ...$values): RuleInterface;

    /**
     * @return RuleInterface
     */
    public function nullable(): RuleInterface;

    /**
     * 指定のiterable値に含まれるか検証
     *
     * @param iterable $values
     * @return $this
     */
    public function in(iterable $values): static;

    /**
     * @param mixed $value
     * @return bool
     */
    public function match(mixed $value): bool;
}
