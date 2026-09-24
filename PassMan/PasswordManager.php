<?php

declare(strict_types=1);

namespace Vanqard\PassMan;

use Vanqard\PassMan\Policy\PolicyInterface;
use Vanqard\PassMan\Strategy\Algorithm;
use Vanqard\PassMan\Strategy\Bcrypt;
use Vanqard\PassMan\Strategy\HashingStrategy;

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
    private ?PolicyInterface $policy = null;

    /**
     * Private class constructor - defer instance acquisition to the static factory method
     *
     * Requires the appropriate algorithm adapter to be passed in
     *
     * @access private - use the factory method
     */
    private function __construct(private readonly HashingStrategy $algorithm)
    {
    }

    /**
     * Simplistic factory method to return a PasswordManager instance already seeded
     * with the algorithm instance to use
     *
     * Example usage
     *
     *   $passwordManager = Vanqard\PassMan\PasswordManager::factory(Algorithm::Bcrypt, ["cost" => 10]);
     *
     * @final Note: This class should not be extended. As such, this method is marked final and the
     * return type is new self. This allows for a mocked PasswordManager in unit testing but
     * prevents accidental or intentional corruption of the interface during normal runtime operation.
     *
     * @param array<string, mixed> $options
     */
    final public static function factory(Algorithm $algorithm = Algorithm::Bcrypt, array $options = []): self
    {
        $strategy = match ($algorithm) {
            Algorithm::Bcrypt => new Bcrypt($options),
        };

        return new self($strategy);
    }

    /**
     * Proxies the password hash request to the specific algorithm strategy instance
     * in use. Optionally validates the supplied password against a password policy instance
     * if one has been supplied
     *
     * Example usage
     *
     *   $hashedPassword = $passwordManager->passwordHash($rawPassword);
     */
    public function passwordHash(string $rawPassword): string
    {
        if ($this->policy instanceof PolicyInterface) {
            $this->validateAgainstPolicy($rawPassword);
        }

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
     */
    public function passwordVerify(string $rawPassword, string $hashedPassword): bool
    {
        return password_verify($rawPassword, $hashedPassword);
    }

    /**
     * Passes the hashedPassword to the algorithm seeded into this password manager
     * instance in order to be checked for compliance with the current system requirements
     */
    public function passwordNeedsRehash(string $hashedPassword): bool
    {
        return $this->algorithm->passwordNeedsRehash($hashedPassword);
    }

    /**
     * Returns an array of information about the provided hash
     *
     * @return array<string, mixed>
     */
    public function passwordGetInfo(string $hashedPassword): array
    {
        return password_get_info($hashedPassword);
    }

    public function setPolicy(PolicyInterface $policy): self
    {
        $this->policy = $policy;
        return $this;
    }

    public function validateAgainstPolicy(string $rawPassword): bool
    {
        $returnValue = false;

        if ($this->policy instanceof PolicyInterface) {
            $this->policy->setRawPassword($rawPassword);
            $returnValue = $this->policy->validatePassword();
        }

        return $returnValue;
    }
}
