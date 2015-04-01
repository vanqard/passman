<?php
namespace Vanqard\PassMan\Strategy;

/**
 * Class definition for the AlgorithmException
 * 
 * Provides only custom error code values for exceptions of this type
 * 
 * @author Thunder Raven-Stoker <thunder@vanqard.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2015 Thunder Raven-Stoker
 */
class AlgorithmException extends \RuntimeException
{
    /**
     * @var integer
     */
    const VPM_ALGORITHM_COST_OUT_OF_RANGE = 1024;
    
    /**
     * @var integer
     */
    const VPM_ALGORITHM_INVALID_OPTION = 2048;
}