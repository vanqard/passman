<?php
namespace Vanqard\PassMan;

use Vanqard\PassMan\PasswordManagerException;
use Vanqard\PassMan\Algorithm\AlgorithmInterface;
use Vanqard\PassMan\Algorithm\Bcrypt;

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
     * @var AlgorithmInterface
     */
    private $algorithm;
    
    /**
     * Class constructor
     * 
     * Requires the appropriate algorithm adapter to be passed in
     * 
     * @param AlgorithmInterface
     * @throws PasswordManagerException
     */
    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Simplistic factory method to return a PasswordManager instance already seeded 
     * with the algorithm instance to use
     * 
     * Example usage
     * 
     *   $passwordManager = Vanqard\PassMan\PasswordManager::factory(PASSWORD_BCRYPT, ["cost" => 10]);
     * 
     * @param integer $algorithmConstant
     * @param array $options
     * @throws PasswordManagerException
     * @return \Vanqard\PassMan\PasswordManager
     */
    public static function factory($algorithmConstant, array $options = [])
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
        
        return new PasswordManager($algorithm);
    }
    
    /**
     * Proxies the password hash request to the specific algorithm instance
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