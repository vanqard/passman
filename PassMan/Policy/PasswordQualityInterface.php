<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Policy;

/**
 * Interface PasswordQualityInterface
 * @package Vanqard\PassMan\Policy
 */
interface PasswordQualityInterface
{
    /**
     * Yields a password strength score of between 1 and 100
     */
    public function getQualityRating(): int;
}
