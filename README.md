# vanqard/passman

This library provides an object oriented wrapper context around the password_hash() functions that are either natively provided in PHP5.5+ or available via Anthony Ferrara's password_compatibility library.

## Intent

To provide a simple to use, lightweight, framework agnostic package that complies with the underlying password_hash() functions that it consumes, whilst also exposing an object oriented interface to those same functions. 

This package exists to simplify following current best practice with regard to the hashing of user passwords. The current absence of an object context for the password_hash() functions results in developers having to write their own, sometimes incorrect, logic to employ those very functions. 

The goal of this package then is to provide a ready made, object oriented implementation that can be community curated for best practice. This includes disabling the ability to provide a custom salt to the underlying password_hash() function thereby ensuring that all salts are dynamically generated. 

## Acknowledgements

This work is inspired by Anthony Ferrara's password hash compatibility library. In some cases, I have even lifted some of his README.md words directly as I thought rewriting them in my own style would be unnecessary and in many cases, I simply couldn't put the idea more succinctly or clearly. 

## Requirements

In order to use the ```password_hash()``` functions, this library requires ```PHP >= 5.3.7```. This is, in itself, to comply with the minimum requirements of the [ircmaxell\password_compat](https://github.com/ircmaxell/password_compat) library that this package depends upon for PHP versions greater than 5.3.7 but less than 5.5, which includes the functions natively. 

## Installation

This package should be installed via composer. Please see [vanqard/passman](http://packagist.org/packages/vanqard/passman).  (coming soon)

The recommended approach is simple to add the following require line

    "vanqard/passman": "*"


## Checking your environment

First of all, before you start using this library in your own projects you should determine the preferred 'cost' value to use for your system. Your goal here is to make the hashing process as expensive as possible but to not impact prohibitively on your user's experience. The PHP Manual [provides a script](http://php.net/manual/en/function.password-hash.php) to allow you to benchmark your system, which I've reproduced here but modified slightly to set the target time higher than the manual's prescribed 50ms.

    <?php
    // You may need to amend this path to locate composer's autoloader
    require('vendor/autoload.php'); 
    
    $timeTarget = 0.25; // 250 milliseconds 

    $cost = 8;
    do {
        $cost++;
        $start = microtime(true);
        password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
        $end = microtime(true);
    } while (($end - $start) < $timeTarget);

    echo "Appropriate Cost Found: " . $cost . "\n";
    ?>

Ideally, you are looking for a cost value that will result in a hashing time of between 100 and 500 milliseconds. The higher the cost value the stronger the resulting hash will be. 

## Usage

Now that you have installed the package, you will then need to obtain a ```PasswordManager``` that has been seeded with the appropriate hashing algorithm. The PasswordManager class exposes a simple factory method to facilitate this. 

The factory method expects an algorithm identifier (based on the PASSWORD_* constants) and optionally an array of options for that algorithm. It is expected that your application will supply these config options by whatever mechanism you would normally employ.


    use Vanqard\PassMan\PasswordManager;
    
    $defaultType = PASSWORD_DEFAULT;
    $defaultOptions = ["cost" => 10];
    
    $passwordManager = PasswordManager::factory($defaultType, $defaultOptions);
    
Once you have your PasswordManager instance, you will then be able to access the relevant methods. The method names are PSR-1 compliant camelCased versions of the native functions names. 

| Method Name                 |  Corresponding Function   |
|---------------------------------|-----------------------------------|
| passwordHash()             |  password_hash()             |
| passwordVerify()           |  password_verify()            |
| passwordNeedsRehash() | password_needs_rehash() |
| passwordGetInfo()          | password_get_info()        |

### Creating password hashes

When you receive a password that needs hashing, you would call the passwordHash() method, like this

    $userPassword = $_POST['password'];
    
    $hashedPassword = $passwordManager->passwordHash($userPassword);
    
You would then store the $hashedPassword value in your database as you would normally.

### Verifying passwords

When a user wants to log in, your code will need to confirm that the password that they supply is the correct one. In this case, you will use the passwordVerify() method to retrieve a boolean true return value for a positive match or a false when the provided password does not match the hashed version that you have stored. 

    $userPassword = $_POST['password'];
    $storedHash = $dbResult['password_hash'];
    
    if ($passwordManager->passwordVerify($userPassword, $storedHash)) {
        // successful match - you may proceed to log the user in
         
            ...
    } else {
        // Passwords mismatch
        throw new \RuntimeException('credentials do not match');    }
    

### Rehashing passwords

From time to time, you may decide that you need to update your hashing parameters (algorithm, cost etc), for example through moving your application to better hardware. 

In this circumstance, you would amend your code to reflect the new hashing parameters, but still bear in mind that the values stored in your database will have been created using the old ones.

This function will allow you to incrementally update the algorithm/cost values of your users' passwords *as they log in*. This will help you to avoid forcing a system wide password reset, which would otherwise be quite disruptive to your users' experience. 

The basic form of the method is thus:

    $boolean = $passwordManager->passwordNeedsRehash($storedHash);
    
And in common usage, you would employ it during the log in process like this:

    // During the login process
    $userPassword = $_POST['password'];
    $storedHash = $dbResult['password_hash'];
    
    if ($passwordManager->passwordVerify($userPassword, $storedHash)) {
        if ($passwordManager->passwordNeedsRehash($storedHash)) {
        	    $newHash = $passwordManager->passwordHash($userPassword);
        	    // store the new hash value in the user's record        }
        // Proceed with your login process
        
    } else {
        // Passwords mismatch
        throw new \RuntimeException('credentials do not match');    }
    
### Security Vulnerabilities

If you have found any security issues with this code, please contact the author directly at [thunder@vanqard.com](mailto:thunder@vanqard.com)
