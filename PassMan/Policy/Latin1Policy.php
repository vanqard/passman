<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Policy;

/**
 * Basic functional implementation of the PolicyInterface
 *
 * Class Latin1Policy
 * @package Vanqard\PassMan\Policy
 */
class Latin1Policy extends AbstractPolicy implements PasswordQualityInterface
{
    public function __construct(
        int $minLength = 8,
        int $minLowerCase = 0,
        int $minUpperCase = 0,
        int $minNumeric = 0,
        int $minSymbols = 0
    ) {
        $this->setMinLength($minLength);
        $this->setMinLowerCase($minLowerCase);
        $this->setMinUpperCase($minUpperCase);
        $this->setMinNumeric($minNumeric);
        $this->setMinSymbols($minSymbols);
    }

    /**
     * @throws PolicyException
     */
    public function validatePassword(): bool
    {
        $this->validateLength();
        $this->validateLowerCase();
        $this->validateUpperCase();
        $this->validateNumeric();
        $this->validateSymbols();

        return true;
    }

    /**
     * @throws PolicyException
     */
    private function validateLength(): bool
    {
        if (strlen($this->rawPassword) < $this->minLength) {
            throw new PolicyException(sprintf("The password should contain at least %d characters", $this->minLength));
        }

        return true;
    }

    /**
     * @throws PolicyException
     */
    private function validateLowerCase(): bool
    {
        $msg = sprintf("The password should contain at least %d lowercase characters", $this->minLowerCase);
        preg_match_all('/([a-z]{1})/', $this->rawPassword, $matches);

        if (count($matches[0]) < $this->minLowerCase) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @throws PolicyException
     */
    private function validateUpperCase(): bool
    {
        $msg = sprintf("The password should contain at least %d uppercase characters", $this->minUpperCase);
        preg_match_all('#([A-Z]{1})#', $this->rawPassword, $matches);

        if (count($matches[0]) < $this->minUpperCase) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @throws PolicyException
     */
    private function validateNumeric(): bool
    {
        $msg = sprintf("The password should contain at least %d numeric characters", $this->minNumeric);
        preg_match_all('/([0-9]{1})/', $this->rawPassword, $matches);

        if (count($matches[0]) < $this->minNumeric) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @throws PolicyException
     */
    private function validateSymbols(): bool
    {
        $msg = sprintf("The password should contain at least %d symbol(s)", $this->minSymbols);
        $pattern = '#([^a-zA-Z0-9]{1})#';
        preg_match_all($pattern, $this->rawPassword, $matches);

        if (count($matches[0]) < $this->minSymbols) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * Scores the raw password out of 100 on three heuristics: raw length (up to 40 points,
     * 2 points/character up to a 20-character cap), breadth of character classes used - lower,
     * upper, numeric, symbol (10 points per class present, up to 40), and depth within those
     * classes (1 point per character beyond the first in each present class, up to 20). This is
     * a simple, dependency-free heuristic, not a substitute for a real entropy/dictionary-based
     * estimator (e.g. zxcvbn) - it deliberately doesn't check for dictionary words or predictable
     * patterns such as "1234" or "qwerty".
     */
    public function getQualityRating(): int
    {
        if ($this->rawPassword === '') {
            return 1;
        }

        preg_match_all('/[a-z]/', $this->rawPassword, $lower);
        preg_match_all('/[A-Z]/', $this->rawPassword, $upper);
        preg_match_all('/[0-9]/', $this->rawPassword, $numeric);
        preg_match_all('/[^a-zA-Z0-9]/', $this->rawPassword, $symbols);

        $lowerCount = count($lower[0]);
        $upperCount = count($upper[0]);
        $numericCount = count($numeric[0]);
        $symbolCount = count($symbols[0]);

        $classesUsed = ($lowerCount > 0 ? 1 : 0)
            + ($upperCount > 0 ? 1 : 0)
            + ($numericCount > 0 ? 1 : 0)
            + ($symbolCount > 0 ? 1 : 0);

        $lengthScore = min(strlen($this->rawPassword) * 2, 40);
        $varietyScore = $classesUsed * 10;
        $depthScore = min(
            max($lowerCount - 1, 0) + max($upperCount - 1, 0) + max($numericCount - 1, 0) + max($symbolCount - 1, 0),
            20
        );

        return min(max($lengthScore + $varietyScore + $depthScore, 1), 100);
    }
}
