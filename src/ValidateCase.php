<?php

declare(strict_types=1);

namespace Haikara\Verifier;

class ValidateCase
{
    protected string $message = '';

    /**
     * @param non-empty-string $name
     * @param mixed $value
     * @param RuleInterface $rule
     */
    public function __construct(protected string $name, protected mixed $value, protected RuleInterface $rule) {
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string {
        return $this->name;
    }

    public function getValue() {
        return $this->rule->filterVar($this->value);
    }

    public function isValid(): bool {
        return $this->rule->match($this->value);
    }

    public function equals($value): bool {
        return $value === $this->getValue();
    }

    public function setMessage(string $message): static {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string {
        return $this->message;
    }
}
