<?php
namespace Vanqard\PassMan\Strategy;

/**
 * Interface specification for the Algorithm strategy implementations consumed by the 
 * PasswordManager instance in this package 
 * 
 * @author Thunder Raven-Stoker <thunder@vanqard.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2015 Thunder Raven-Stoker
 */
interface HashingStrategy
{
    /**
     * @param array $options
     */
    public function setOptions(array $options = []);

    /**
     * @param string $optionName
     */
    public function getOption($optionName);

    /**
     * @param string $rawPassword
     */
    public function passwordHash($rawPassword);
    
    /**
     * @param string $hashedPassword
     */
    public function passwordNeedsRehash($hashedPassword);
}