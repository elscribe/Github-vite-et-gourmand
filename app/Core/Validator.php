<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;

/**
 * Centralise la validation serveur des donnees issues des formulaires.
 */
final class Validator
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, list<string>> $rules
     */
    private function __construct(
        private readonly array $data,
        private readonly array $rules,
        private array $errors = []
    ) {
        $this->validate();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, list<string>> $rules
     */
    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function fails(): bool
    {
        return ! $this->passes();
    }

    /**
     * @return array<string, list<string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function first(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;
            $isRequired = in_array('required', $rules, true);

            if (! $isRequired && $this->isEmpty($value)) {
                continue;
            }

            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        [$ruleName, $parameter] = $this->parseRule($rule);

        match ($ruleName) {
            'required' => $this->validateRequired($field, $value),
            'email' => $this->validateEmail($field, $value),
            'min' => $this->validateMin($field, $value, $parameter),
            'max' => $this->validateMax($field, $value, $parameter),
            'int' => $this->validateInt($field, $value),
            'positive' => $this->validatePositive($field, $value),
            'in' => $this->validateIn($field, $value, $parameter),
            'password' => $this->validatePassword($field, $value),
            default => throw new InvalidArgumentException('Unknown validation rule: ' . $ruleName),
        };
    }

    /**
     * @return array{0: string, 1: string|null}
     */
    private function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);

        return [$parts[0], $parts[1] ?? null];
    }

    private function validateRequired(string $field, mixed $value): void
    {
        if ($this->isEmpty($value)) {
            $this->addError($field, 'Le champ est obligatoire.');
        }
    }

    private function validateEmail(string $field, mixed $value): void
    {
        if (! is_string($value) || filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($field, 'Le format de l\'adresse email est invalide.');
        }
    }

    private function validateMin(string $field, mixed $value, ?string $parameter): void
    {
        $minimum = $this->integerParameter('min', $parameter);

        if (strlen(trim((string) $value)) < $minimum) {
            $this->addError($field, 'Le champ doit contenir au moins ' . $minimum . ' caracteres.');
        }
    }

    private function validateMax(string $field, mixed $value, ?string $parameter): void
    {
        $maximum = $this->integerParameter('max', $parameter);

        if (strlen(trim((string) $value)) > $maximum) {
            $this->addError($field, 'Le champ ne doit pas depasser ' . $maximum . ' caracteres.');
        }
    }

    private function validateInt(string $field, mixed $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->addError($field, 'Le champ doit etre un nombre entier.');
        }
    }

    private function validatePositive(string $field, mixed $value): void
    {
        if (! is_numeric($value) || (float) $value <= 0) {
            $this->addError($field, 'Le champ doit etre un nombre positif.');
        }
    }

    private function validateIn(string $field, mixed $value, ?string $parameter): void
    {
        if ($parameter === null || $parameter === '') {
            throw new InvalidArgumentException('The in rule requires accepted values.');
        }

        $acceptedValues = array_map('trim', explode(',', $parameter));

        if (! in_array((string) $value, $acceptedValues, true)) {
            $this->addError($field, 'La valeur selectionnee est invalide.');
        }
    }

    private function validatePassword(string $field, mixed $value): void
    {
        if (! is_string($value)) {
            $this->addError($field, 'Le mot de passe est invalide.');
            return;
        }

        $hasMinimumLength = strlen($value) >= 10;
        $hasLowercase = preg_match('/[a-z]/', $value) === 1;
        $hasUppercase = preg_match('/[A-Z]/', $value) === 1;
        $hasDigit = preg_match('/\d/', $value) === 1;
        $hasSpecialCharacter = preg_match('/[^a-zA-Z0-9]/', $value) === 1;

        if (! $hasMinimumLength || ! $hasLowercase || ! $hasUppercase || ! $hasDigit || ! $hasSpecialCharacter) {
            $this->addError(
                $field,
                'Le mot de passe doit contenir au moins 10 caracteres, une majuscule, une minuscule, un chiffre et un caractere special.'
            );
        }
    }

    private function integerParameter(string $rule, ?string $parameter): int
    {
        if ($parameter === null || filter_var($parameter, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('The ' . $rule . ' rule requires an integer parameter.');
        }

        return (int) $parameter;
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null
            || (is_string($value) && trim($value) === '')
            || (is_array($value) && $value === []);
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}
