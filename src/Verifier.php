<?php

declare(strict_types=1);

namespace Haikara\Verifier;

use Haikara\Verifier\Exception\InvalidValueException;

/**
 * ライブラリのルートオブジェクトになるクラス
 */
class Verifier
{
    /**
     * @var array<string, ValidateCase>
     */
    protected array $cases = [];

    /**
     * 検証成功
     */
    protected const VALID = true;

    /**
     * 検証失敗
     */
    protected const INVALID = false;

    /**
     * @param non-empty-string $name
     * @param mixed $value
     * @param RuleInterface $rule
     * @return ValidateCase
     * @throws InvalidValueException
     */
    public function case(string $name, mixed $value, RuleInterface $rule): ValidateCase {
        if (array_key_exists($name, $this->cases)) {
            throw new InvalidValueException("{$name}は登録済みです。");
        }

        return $this->cases[$name] = new ValidateCase($name, $value, $rule);
    }

    public function verify(): bool {
        foreach ($this->cases as $param) {
            if (!$param->isValid()) {
                return static::INVALID;
            }
        }

        return static::VALID;
    }

    public function getMessages(): array {
        $messages = [];

        foreach ($this->cases as $name => $param) {
            if (!$param->isValid()) {
                $messages[$name] = $param->getMessage();
            }
        }

        return $messages;
    }

    /**
     * @param non-empty-string $name
     * @return ?ValidateCase
     */
    public function getCase(string $name): ?ValidateCase {
        return $this->cases[$name] ?? null;
    }

    /**
     * @return array<string ,ValidateCase>
     */
    public function getCases(): array {
        return $this->cases;
    }
}
