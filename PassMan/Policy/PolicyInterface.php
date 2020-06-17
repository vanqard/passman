<?php
namespace Vanqard\PassMan\Policy;

interface PolicyInterface
{
    /**
     * @param int $minLength
     */
    public function setMinLength($minLength);

    /**
     * @param int $minUpperCase
     */
    public function setMinUpperCase($minUpperCase);

    /**
     * @param int $minLowerCase
     */
    public function setMinLowerCase($minLowerCase);

    /**
     * @param int $minNumeric
     */
    public function setMinNumeric($minNumeric);

    /**
     * @param int $minSymbols
     */
    public function setMinSymbols($minSymbols);

    /**
     * @param int $rawPassword
     */
    public function setRawPassword($rawPassword);

    /**
     * @throws PolicyException
     * @return bool
     */
    public function validatePassword();
}