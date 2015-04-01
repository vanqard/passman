<?php
namespace Vanqard\PassMan;

/**
 * Class definition for the PasswordManagerException
 * 
 * Provides only custom error code values for exceptions of this type
 * 
 * @author Thunder Raven-Stoker <thunder@vanqard.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2015 Thunder Raven-Stoker
 */
class PasswordManagerException extends \RuntimeException
{
    /**
     * @var integer
     */
    const VPM_ERROR_CODE_NO_SALT = 128;
    
    /**
     * @var integer
     */
    const VPM_ERROR_CODE_COST_NOT_INTEGER = 512;
    
}
