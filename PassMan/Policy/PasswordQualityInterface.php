<?php
namespace Vanqard\PassMan\Policy;

/**
 * Interface PasswordQualityInterface
 * @package Vanqard\PassMan\Policy
 */
interface PasswordQualityInterface
{
    /**
     * Yields a password strength score of between 0 and 100
     * @return int
     */
    public function getQualityRating();
}