# Changelog


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
