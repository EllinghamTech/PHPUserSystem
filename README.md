# Ellingham Tech: PHP User System
[![Build Status](https://travis-ci.org/EllinghamTech/PHPUserSystem.svg?branch=master)](https://travis-ci.org/EllinghamTech/PHPUserSystem)
[![codecov](https://codecov.io/gh/EllinghamTech/PHPUserSystem/branch/master/graph/badge.svg)](https://codecov.io/gh/EllinghamTech/PHPUserSystem)
[![Latest Stable Version](https://poser.pugx.org/ellingham-technologies/phpusersystem/v/stable)](https://packagist.org/packages/ellingham-technologies/phpusersystem)
[![Latest Unstable Version](https://poser.pugx.org/ellingham-technologies/phpusersystem/v/unstable)](https://packagist.org/packages/ellingham-technologies/phpusersystem)
[![Tested PHP Versions](https://img.shields.io/badge/php-%3E%3D7.1.0-green.svg)](https://www.php.net/releases/)

A user management solution for PHP.  It could be said that this is a framework.  But it only deals
with user management.  The rest is up to you.

Written with PHP 7 and utilising data typing for methods and method parameters.

**Features**
- User Accounts (register, login, forgot password features)
- User Profiles (public facing user information)
- User Permissions (Read, Write, Execute, Modify, Delete + custom permissions)
- User Limits (similar to permissions, but when you need a limit...)
- User Tokens (for forgot-password, email verification, etc)
- User Preference (store preferences for your users!)
- User Meta (store additional data for the user, such as backup email address)
- Administrative Tools (a set of pre-built administration helpers)

**Future**
- Two-factor auth
- Data Caching

```php
use EllinghamTech\Database\SQL\MySQL;
use EllinghamTech\PHPUserSystem\UserSystem;

$database = new MySQL(); // Also support SQLite: new SQLite();
$database->connect($host, $user, $pass, $dbname);

UserSystem::init($database);
$currentUser = UserSystem::session()->user();
```

## Database support
Currently supporting MySQL and SQLite using the Database wrappers provided in EllinghamTech PHPHelpers.

If you are already using PDO, you do not need to create a new connection.  Simply use:
```php
use EllinghamTech\PHPUserSystem\UserSystem;

$database = new EllinghamTech\Database\SQL\Wrapper(); // MySQL() for MySQL, SQLite() for SQLite
$database->useExistingConnection($pdoObject);
UserSystem::init($database);
```

## Installation
### With Composer
Using Composer (https://getcomposer.org) simply run
```
composer require ellingham-technologies/phpusersystem
```

or add this line to the require section of your composer.json file and use composer to update/install:
```
"ellingham-technologies/phpusersystem": "dev-master",
```

*As this is early days, we should point out that the library is quite limited.  You can use the "dev-master" release (which is the latest and greatest) - but this could always lead to site-breakages if there is an error somewhere in the development library - so don't use on production sites.*

### Without Composer
We've included a custom AutoLoader (src/EllinghamTech/PHPUserSystem/AutoLoad.php) that can be used or you can
include the classes individually as you please. 

Note that his library requires [EllinghamTech/PHPHelpers](https://github.com/EllinghamTech/PHPHelpers).
**Currently this has not been tested without composer.**

## Dependencies
- PHP 7.1 or above
- PDO (SQL/MySQL or SQLite supported)
- **Composer Managed:**
    - [EllinghamTech/PHPHelpers](https://github.com/EllinghamTech/PHPHelpers). (composer: ellingham-technologies/phphelpers), latest version (>0.4)

The EllinghamTech/PHPUserSystem and EllinghamTech/PHPHelpers namespaces include all of the classes required for this library.

See samples/ for details on how to implement these features on your site.

## Setup
This library requires a specific set of tables in the database.  You can find the SQL queries to
create the tables and recommended indexes in MySQL_tables.md and SQLite_tables.md.

We plan to build an installation and update script in the future.  Until then, you will need to make
manual changes if the tables change.  Ensure you read the release notes before updating.

## Factory and object initialisation
Many objects are created by a factory instance.  This removes some of the complexities when setting
up certain objects correctly.  Other instances can be created by the "parent" instance.

```php
use EllinghamTech\Database\SQL\MySQL;
use EllinghamTech\PHPUserSystem\UserSystem;
use EllinghamTech\PHPUserSystem\UserFactory;

$database = new MySQL(); // Also supports SQLite: new SQLite();
// Already using PDO?
// Then use $database->useExistingConnection($pdo_object_here);
// You can also set the database type with $database->database_type = 'sql';
$database->connect($host, $user, $pass, $dbname);

UserSystem::init($database);

// Create a user object using the factory
$user = UserFactory::newUser(); // Instance of User

$user->user_name = 'NewUser';
$user->user_email = 'example@example.com';
$user->setPassword('a_password_here'); // setPassword will handle password hashing for you

$user->save(); // We now have a new user in the database!

// Loading user with factory
$user = UserFactory::getUserByUserName('NewUser'); // Instance of User

// Loading user permission
$userPermission = $user->getUserPermission('posts'); // Instance of UserPermission

$canModifyPost = $userPermission->hasPermission('m'); // Bool
$canWritePost = $userPermission->hasPermission('w'); // Bool, write = add in this context

// Get a user limit
$userPostLimit = $user->getUserLimit('daily_posts'); // Instance of UserLimit
$canMakeOnePost = $userPostLimit->isWithinLimit(); // Bool
$canMakeTwoPosts = $userPostLimit->isWithinLimit(2); // Bool

if($canMakeOnePost)
{
    // do the post stuff
    $userPostLimit->drawDown(1); // Decreases current limit value by 1
}

```
