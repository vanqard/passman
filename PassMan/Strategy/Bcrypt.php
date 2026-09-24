<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Strategy;

use Vanqard\PassMan\Exception\AlgorithmException;

/**
 * Class definition for the Bcrypt implementation of the HashingStrategy
 *
 * @author Thunder Raven-Stoker <thunder@vanqard.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2015 Thunder Raven-Stoker
 */
class Bcrypt implements HashingStrategy
{
    /**
     * @var string
     */
    public const VPM_ALGORITHM = PASSWORD_BCRYPT;

    /**
     * @var integer
     */
    public const VPM_BCRYPT_DEFAULT_COST = 10;

    /**
     * @var array{cost: int}
     */
    private array $options = [
        'cost' => self::VPM_BCRYPT_DEFAULT_COST,
    ];

    /**
     * Constructor
     *
     * Defers options setting to the setter for validation and filtering
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Options array setter for this algorithm strategy. Presently only accepts a cost option
     *
     * @param array<string, mixed> $options
     * @see \Vanqard\PassMan\Strategy\HashingStrategy::setOptions()
     * @throws \Vanqard\PassMan\Exception\AlgorithmException
     */
    public function setOptions(array $options = []): static
    {
        if (empty($options) || !array_key_exists('cost', $options)) {
            $options['cost'] = self::VPM_BCRYPT_DEFAULT_COST;
        }

        if (is_numeric($options['cost'])) {

            $cost = (int) $options['cost'];

            if ($cost < 4 || $cost > 31) {
                throw new AlgorithmException(
                    sprintf('Cost option value should be between 4 and 31. %s supplied', $cost),
                    AlgorithmException::VPM_ALGORITHM_COST_OUT_OF_RANGE
                );
            }

            $this->options['cost'] = $cost;
        }

        // No other options considered for this algo
        return $this;
    }

    /**
     * Only able to return the 'cost' option. The salt option is disabled for security reasons
     * as the underlying function will provide a much more secure dynamic salt
     *
     * @see \Vanqard\PassMan\Strategy\HashingStrategy::getOption()
     * @throws \Vanqard\PassMan\Exception\AlgorithmException
     */
    public function getOption(string $optionName = 'cost'): int
    {
        if ($optionName !== 'cost') {
            throw new AlgorithmException(
                sprintf('Bcrypt algo only supports a cost option. %s supplied', $optionName),
                AlgorithmException::VPM_ALGORITHM_INVALID_OPTION
            );
        }

        return $this->options['cost'];
    }

    /**
     * Generate a new password hash based on the incoming user supplied $rawPassword parameter
     *
     * The algorithm to use to pre-configured to employ the PASSWORD_DEFAULT constant as this may
     * change when better algorithms are introduced into the core.
     */
    public function passwordHash(string $rawPassword): string
    {
        return password_hash($rawPassword, self::VPM_ALGORITHM, $this->options);
    }

    /**
     * Test the hashedPassword to determine whether it needs 'upgrading'
     *
     * The incoming $hashedPassword parameter will be tested against this algo
     * to see whether the $hashedPassword needs to be rehashed
     */
    public function passwordNeedsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, self::VPM_ALGORITHM, $this->options);
    }
}
