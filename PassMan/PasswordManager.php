<?php
namespace Vanqard\PassMan;

use Vanqard\PassMan\Exception\PasswordManagerException;
use Vanqard\PassMan\Strategy\HashingStrategy;
use Vanqard\PassMan\Strategy\Bcrypt;

/**
 * Class definition for the Vanqard\PassMan\PasswordManager class
 * 
 * Provides an object oriented wrapper context around the password_hash() function,
 * and deliberately disables the user supplied salt facility to encourage the use of
 * dynamic salt generation available inside the function itself
 * 
 * 
 * @author Thunder Raven-Stoker <thunder@vanqard.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2015 Thunder Raven-Stoker
 */
class PasswordManager
{
    /**
     * @var HashingStrategy
     */
    private $algorithm;
    
    /**
     * Private class constructor - defer instance acquisition to the static factory method
     * 
     * Requires the appropriate algorithm adapter to be passed in
     * 
     * @access private - use the factory method
     * @param HashingStrategy
     * @throws PasswordManagerException
     */
    private function __construct(HashingStrategy $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Simplistic factory method to return a PasswordManager instance already seeded 
     * with the algorithm instance to use
     * 
     * Example usage (with PHP5.3.7 compliant array() declaration rather than square bracket notation)
     * 
     *   $passwordManager = Vanqard\PassMan\PasswordManager::factory(PASSWORD_BCRYPT, array("cost" => 10));
     *   
     * @final Note: This class should not be extended. As such, this method is marked final and the 
     * return type is new self. This allows for a mocked PasswordManager in unit testing but
     * prevents accidental or intentional corruption of the interface during normal runtime operation. 
     * 
     * @param integer $algorithmConstant
     * @param array $options
     * @throws PasswordManagerException
     * @return \Vanqard\PassMan\PasswordManager
     */
    final public static function factory($algorithmConstant = PASSWORD_DEFAULT, array $options = array())
    {
        switch ( $algorithmConstant) {

        	case PASSWORD_DEFAULT: // Default case will need to be moved when new algos become available and assigned as default
        	case PASSWORD_BCRYPT:
        	    $algorithm = new Bcrypt($options);
        	    break;
        	default:
        	    throw new PasswordManagerException(
    	           sprintf('Unidentified hashing algorithm. %s supplied', $algorithmConstant)
        	    );
        	    break;
        }
        
        return new self($algorithm);
    }
    
    /**
     * Proxies the password hash request to the specific algorithm strategy instance
     * in use. 
     * 
     * Example usage
     * 
     *   $hashedPassword = $passwordManager->passwordHash($rawPassword);
     * 
     * @param string $rawPassword
     * @return string|false The hashed password or false on error
     */
    public function passwordHash($rawPassword)
    {
        return $this->algorithm->passwordHash($rawPassword);
    }
    
    /**
     * Determines whether the supplied $rawPassword matches the supplied $hashedPassword
     *
     *  Example usage
     *  
     *    $rawPassword = $_POST['login']['password'];
     *    $hashedPassword = $dbResultSet['password_hash_column'];
     *
     *    $passwordsMatch = $passwordManager->verifyPassword($rawPassword, $hashedPassword);
     *
     * @param string $rawPassword
     * @param string $hashedPassword
     * @return boolean
     */
    public function passwordVerify($rawPassword, $hashedPassword)
    {
        return password_verify($rawPassword, $hashedPassword);
    }
    
    /**
     * Passes the hashedPassword to the algorithm seeded into this password manager
     * instance in order to be checked for compliance with the current system requirements
     * 
     * @param string $hashedPassword
     * @return boolean
     */
    public function passwordNeedsRehash($hashedPassword)
    {
        return $this->algorithm->passwordNeedsRehash($hashedPassword);
    }
    
    /**
     * Returns an array of information about the provided hash
     * 
     * @param string $hashedPassword
     * @return array
     */
    public function passwordGetInfo($hashedPassword)
    {
        return password_get_info($hashedPassword);
    }
}