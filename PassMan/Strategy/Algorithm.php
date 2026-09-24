<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Strategy;

/**
 * Enumerates the hashing algorithm families supported by PasswordManager::factory().
 *
 * Bcrypt is PHP's current PASSWORD_DEFAULT. Since PASSWORD_DEFAULT and PASSWORD_BCRYPT
 * currently resolve to the identical value ('2y'), a single Bcrypt case represents both -
 * there is no meaningful distinction to model as separate enum cases. If/when PHP's default
 * changes to a different algorithm, this enum's default case (see the parameter default in
 * PasswordManager::factory()) can be repointed without a breaking API change to consumers
 * who rely on the implicit default parameter value.
 */
enum Algorithm: string
{
    case Bcrypt = PASSWORD_BCRYPT;

    // Future cases (once Strategy implementations exist):
    // case Argon2i  = PASSWORD_ARGON2I;
    // case Argon2id = PASSWORD_ARGON2ID;
}
