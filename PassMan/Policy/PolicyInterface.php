<?php
namespace Vanqard\PassMan\Policy;

interface PolicyInterface
{
    public function setMinLength($minLength);

    public function setMinUpperCase($minUpperCase);

    public function setMinLowerCase($minLowerCase);

    public function setMinNumeric($minNumeric);

    public function setMinSymbols($minSymbols);

    public function setRawPassword($rawPassword);

    public function validatePassword();

    public function getQualityRating();
}