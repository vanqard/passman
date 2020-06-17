<?php
namespace Vanqard\PassMan\Policy;

/**
 * Class AbstractPolicy
 * @package Vanqard\PassMan\Policy
 */
abstract class AbstractPolicy implements PolicyInterface
{
    /**
     * @var int
     */
    protected $minLength = 8;

    /**
     * @var int
     */
    protected $minLowerCase = 0;

    /**
     * @var int
     */
    protected $minUpperCase = 0;

    /**
     * @var int
     */
    protected $minNumeric = 0;

    /**
     * @var int
     */
    protected $minSymbols = 0;

    /**
     * @var string
     */
    protected $rawPassword;

    /**
     * @param $minLength
     */
    public function setMinLength($minLength)
    {
        $this->minLength = (int) $minLength;
    }

    /**
     * @param $minLowerCase
     */
    public function setMinLowerCase($minLowerCase)
    {
        $this->minLowerCase = (int) $minLowerCase;
    }

    /**
     * @param $minUpperCase
     */
    public function setMinUpperCase($minUpperCase)
    {
        $this->minUpperCase = (int) $minUpperCase;
    }

    /**
     * @param $minNumeric
     */
    public function setMinNumeric($minNumeric)
    {
        $this->minNumeric = (int) $minNumeric;
    }

    /**
     * @param $minSymbols
     */
    public function setMinSymbols($minSymbols)
    {
        $this->minSymbols = (int) $minSymbols;
    }

    /**
     * @param string $rawPassword
     */
    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    /**
     * @return string
     */
    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    /**
     * @return bool
     * @throws PolicyException
     */
    abstract public function validatePassword();
}