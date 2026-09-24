<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Policy;

interface PolicyInterface
{
    public function setMinLength(int $minLength): void;

    public function setMinUpperCase(int $minUpperCase): void;

    public function setMinLowerCase(int $minLowerCase): void;

    public function setMinNumeric(int $minNumeric): void;

    public function setMinSymbols(int $minSymbols): void;

    public function setRawPassword(string $rawPassword): void;

    /**
     * @throws PolicyException
     */
    public function validatePassword(): bool;
}
