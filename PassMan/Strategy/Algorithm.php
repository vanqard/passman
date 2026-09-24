<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Strategy;

/**
 * Enumerates the hashing algorithm families supported by PasswordManager::factory().
 *
 * Bcrypt is PHP's current PASSWORD_DEFAULT. Since PASSWORD_DEFAULT and PASSWORD_BCRYPT
 * currently resolve to the identical value, a single Bcrypt case represents both - there
 * is no meaningful distinction to model as separate enum cases. If/when PHP's default
 * changes to a different algorithm, this enum's default case (see the parameter default in
 * PasswordManager::factory()) can be repointed without a breaking API change to consumers
 * who rely on the implicit default parameter value.
 *
 * This is a pure (unbacked) enum rather than one backed by PASSWORD_BCRYPT: extension-registered
 * constants like PASSWORD_BCRYPT aren't accepted as enum case values on PHP 8.1 ("Enum case value
 * must be compile-time evaluatable"), a restriction later PHP versions relax. Nothing in this
 * library reads a backing value, so there's nothing to give up by leaving the enum unbacked.
 */
enum Algorithm
{
    case Bcrypt;

    // Future cases (once Strategy implementations exist):
    // case Argon2i;
    // case Argon2id;
}
