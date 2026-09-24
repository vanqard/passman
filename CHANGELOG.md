# Changelog

## 3.0.0 - PHP 8.1 modernization

 * In file: composer.json
     * Raised minimum PHP requirement from >=5.3.7 to ^8.1
     * Removed the ircmaxell/password-compat dependency (no longer needed; PHP 8.1 provides all password_* functions natively)
     * Upgraded phpunit/phpunit dev requirement to ^10.0
     * Added phpstan/phpstan and friendsofphp/php-cs-fixer as dev tooling
 * In file: PassMan/Strategy/Algorithm.php (new)
     * Introduced a native backed enum, Vanqard\PassMan\Strategy\Algorithm, to represent selectable hashing algorithm families
 * In file: PassMan/PasswordManager.php
     * factory() now accepts an Algorithm enum instance (default Algorithm::Bcrypt) in place of a raw PASSWORD_* constant
     * factory() no longer throws PasswordManagerException for an invalid algorithm selector, since the type system now rejects invalid input at the call boundary
 * In file: PassMan/Exception/PasswordManagerException.php
     * Removed the unused VPM_ERROR_CODE_NO_SALT and VPM_ERROR_CODE_COST_NOT_INTEGER constants (never thrown anywhere in the codebase)
 * All files under PassMan/
     * Added declare(strict_types=1) and full parameter/return/property type declarations throughout
     * Converted all array() syntax to [] short array syntax
     * In PassMan/Policy/Latin1Policy.php, removed redundant validation branches in the length/case/numeric/symbol checks that were always subsumed by the subsequent count() comparison
 * Test suite
     * Migrated test/Unit/*.php from PHPUnit 4-era PHPUnit_Framework_TestCase / @expectedException annotations to PHPUnit\Framework\TestCase / expectException()/expectExceptionCode() calls
     * Removed PasswordManagerTest::testFactoryInvalidCallThrowsException(), superseded by compile-time type safety on factory()'s Algorithm parameter
 * CI/tooling
     * Replaced Travis CI (.travis.yml) with a GitHub Actions workflow (.github/workflows/tests.yml) testing PHP 8.1-8.4
     * Replaced travis.phpunit.xml with phpunit.xml.dist using the PHPUnit 10 configuration schema
     * Added phpstan.neon (level max) and .php-cs-fixer.dist.php (PSR-12) configs
 * BC issues:
     * PHP >=5.3.7 through <8.1 is no longer supported
     * PasswordManager::factory()'s first parameter is now type-hinted Algorithm instead of accepting a raw int/string PASSWORD_* constant; passing anything other than an Algorithm case now raises a TypeError
     * ircmaxell/password-compat is no longer pulled in as a transitive dependency
     * Removed the unused PasswordManagerException::VPM_ERROR_CODE_NO_SALT and VPM_ERROR_CODE_COST_NOT_INTEGER constants

## 2.0.2 - Mod version requirement of ircmaxell/password-compat
 * In file: composer.json - removed wildcard * version requirement and replaced with ^1.0.4

## 2.0.1 - Improved packagist description only
 * In file: composer.json  (improved package descripton)
 
## 2.0.0 - Exception refactoring requires new major version

 * In file: PasswordManager.php
     * Set final keyword on static factory method to reinforce non-inheritance during runtime execution but still permit the PasswordManager class to be mocked
     * Added PASSWORD_DEFAULT as default for the $algorithmConstant parameter to the static factory method.
     * Replaced square bracket notation to array() for PHP5.3.7+ compatibility
   * Updated and extended unit test coverage. Also now includes test for test mock compliance
   * BC issues:
       * Refactored exception classes into their own sub namespace
       * Constructor access changed from public to private to defer instantiation to static 

## 1.0.0 - First package release
