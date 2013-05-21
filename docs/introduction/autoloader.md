# Autoloader

Scaffold comes with it's own word class autoloader that is so smart that most of the time you won't even need to think about it, it **Just Works**&trade;. Just use a class, interface, or trait, and the Autoloader will step in if it hasn't already been defined.

However, because it is an autoloader, you do have to follow rules regarding how you name your classes, and were you put them.

## Rules

There are only two rules when it comes to naming classes in Scaffold

 - Capital letters become directory seperators, unless they are followed by another capitalised letter.
 - If a pluralised folder exists, class *parts* will also be pluralised unless there class exists without being pluralised.

For example, 

```php

// Folder Structure
[
    'controllers' => [
        'user.php'
    ],

    'models' => [
        'user.php'
    ],

    'database' =>
        'query' => [
            'builder.php'
        ],
        'driver' => [
            'pdo.php'
        ]
    ],

    'hash.php'
];

ControllerUser;
// controllers/user.php

ModelUser;
// models/user.php

DatabaseQueryBuilder;
// database/query/builder.php

DatabaseDriverPDO;
// database/driver/pdo.php

Hash;
// hash.php
```

## Manually Register Classes

We recognise that these rules aren't the most flexible, so if you need to use classes that don't follow these naming conventions, you can manually register these classes.

```php
DatabaseQueryBuilderSQLite;
// database/query/builder/sql/ite.php

Autoload::register('DatabaseQueryBuilderSQLite', 'database/query/builder/sqlite.php');

DatabaseQueryBuilderSQLite;
// database/query/builder/sqlite.php
```
