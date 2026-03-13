<?php

class Validator
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function requireName(string $value, string $field): void
    {
        if ($value === '' || !preg_match('/^[A-Za-z]+$/', $value)) {
            $this->errors[] = "{$field} is required and must contain letters only.";
        }
    }

    public function requireNotEmpty(string $value, string $field): void
    {
        if ($value === '') {
            $this->errors[] = "{$field} is required.";
        }
    }

    public function requireCountry(string $value): void
    {
        if ($value === '' || $value === 'Select Country') {
            $this->errors[] = 'Country is required.';
        }
    }

    public function requireGender(string $value): void
    {
        if ($value !== 'Male' && $value !== 'Female') {
            $this->errors[] = 'Gender is required.';
        }
    }

    public function requireSkills(array $skills): void
    {
        if (count($skills) === 0) {
            $this->errors[] = 'At least one skill must be selected.';
        }
    }

    public function requirePassword(string $value): void
    {
        if (!preg_match('/^[a-z0-9_]{8}$/', $value)) {
            $this->errors[] = 'Password must be exactly 8 characters: lowercase letters, numbers, and underscore only.';
        }
    }

    public function requireCode(string $value, string $expected): void
    {
        if ($value !== $expected) {
            $this->errors[] = 'Invalid registration code.';
        }
    }
}
