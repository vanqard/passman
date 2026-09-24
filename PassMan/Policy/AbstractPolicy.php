<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Policy;

/**
 * Class AbstractPolicy
 * @package Vanqard\PassMan\Policy
 */
abstract class AbstractPolicy implements PolicyInterface
{
    protected int $minLength = 8;

    protected int $minLowerCase = 0;

    protected int $minUpperCase = 0;

    protected int $minNumeric = 0;

    protected int $minSymbols = 0;

    protected string $rawPassword = '';

    public function setMinLength(int $minLength): void
    {
        $this->minLength = $minLength;
    }

    public function setMinLowerCase(int $minLowerCase): void
    {
        $this->minLowerCase = $minLowerCase;
    }

    public function setMinUpperCase(int $minUpperCase): void
    {
        $this->minUpperCase = $minUpperCase;
    }

    public function setMinNumeric(int $minNumeric): void
    {
        $this->minNumeric = $minNumeric;
    }

    public function setMinSymbols(int $minSymbols): void
    {
        $this->minSymbols = $minSymbols;
    }

    public function setRawPassword(string $rawPassword): void
    {
        $this->rawPassword = $rawPassword;
    }

    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }

    /**
     * @throws PolicyException
     */
    abstract public function validatePassword(): bool;
}
