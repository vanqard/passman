<?php
namespace Vanqard\PassMan\Policy;

/**
 * Basic functional implementation of the PolicyInterface
 *
 * Class Latin1Policy
 * @package Vanqard\PassMan\Policy
 */
class Latin1Policy extends AbstractPolicy
{
    /**
     * Policy constructor.
     * @param int $minLength
     * @param int $minLowerCase
     * @param int $minUpperCase
     * @param int $minNumeric
     * @param int $minSymbols
     */
    public function __construct(
        $minLength = 8,
        $minLowerCase = 0,
        $minUpperCase = 0,
        $minNumeric = 0,
        $minSymbols = 0
    )
    {
        $this->setMinLength($minLength);
        $this->setMinLowerCase($minLowerCase);
        $this->setMinUpperCase($minUpperCase);
        $this->setMinNumeric($minNumeric);
        $this->setMinSymbols($minSymbols);
    }

    /**
     * @return float|int
     */
    public function getQualityRating()
    {
        // @TODO - pending implementation
        return 0;
    }

    /**
     * @throws PolicyException
     */
    public function validatePassword()
    {
        $this->validateLength();
        $this->validateLowerCase();
        $this->validateUpperCase();
        $this->validateNumeric();
        $this->validateSymbols();

        return true;
    }

    /**
     * @return bool
     * @throws PolicyException
     */
    private function validateLength()
    {
        if (strlen($this->rawPassword) < $this->minLength) {
            throw new PolicyException(sprintf("The password should contain at least %d characters", $this->minLength));
        }

        return true;
    }

    /**
     * @throws PolicyException
     * @return bool
     */
    private function validateLowerCase()
    {
        $msg = sprintf("The password should contain at least %d lowercase characters", $this->minLowerCase);
        preg_match_all('/([a-z]{1})/', $this->rawPassword, $matches);

        if ($this->minLowerCase > 0 && (!isset($matches[0]) || empty($matches[0]))) {
            throw new PolicyException($msg);
        }

        if (count($matches[0]) < $this->minLowerCase) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @return bool
     * @throws PolicyException
     */
    private function validateUpperCase()
    {
        $msg = sprintf("The password should contain at least %d uppercase characters", $this->minUpperCase);
        preg_match_all('#([A-Z]{1})#', $this->rawPassword, $matches);

        if ($this->minUpperCase > 0 && (!isset($matches[0]) || empty($matches[0]))) {
            throw new PolicyException($msg);
        }

        if (count($matches[0]) < $this->minUpperCase) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @return bool
     * @throws PolicyException
     */
    private function validateNumeric()
    {
        $msg = sprintf("The password should contain at least %d numeric characters", $this->minNumeric);
        preg_match_all('/([0-9]{1})/', $this->rawPassword, $matches);

        if ($this->minNumeric > 0 && (!isset($matches[0]) || empty($matches[0]))) {
            throw new PolicyException($msg);
        }

        if (count($matches[0]) < $this->minNumeric) {
            throw new PolicyException($msg);
        }

        return true;
    }

    /**
     * @return bool
     * @throws PolicyException
     */
    private function validateSymbols()
    {
        $msg = sprintf("The password should contain at least %d symbol(s)", $this->minSymbols);
        $pattern = '#([^a-zA-Z0-9]{1})#';
        preg_match_all($pattern, $this->rawPassword, $matches);

        if ($this->minSymbols > 0 && (!isset($matches[0]) || empty($matches[0]))) {
            throw new PolicyException($msg);
        }

        if (count($matches[0]) < $this->minSymbols) {
            throw new PolicyException($msg);
        }

        return true;
    }
}