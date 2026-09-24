<?php

declare(strict_types=1);

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
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): static;

    public function getOption(string $optionName): mixed;

    public function passwordHash(string $rawPassword): string;

    public function passwordNeedsRehash(string $hashedPassword): bool;
}
