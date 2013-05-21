# Configuration

Scaffold is highly configurable, and comes with a built-in `Config` class for you to use in your own applications. Several of Scaffold's built-in systems also use the `Config` class, including the database system. 

Configuration in Scaffold is based on the `SCAFFOLD_ENV` environment variable that you should set in your server configuration. Setting this in your application boostrap is **not** currently supported.

To configure Scaffold, create a folder at `application/config` and create files for each system you want to configure. 

## Class Usage

To use the `Config` class, you first have to *get* it using the `Service` class. You can then use the `get` method to get various configuration information. The `Config` class uses dot-seperated notation for naming. 

For example, to get the whole contents of the `database` configuration array, you would use the following.

```php
Service::get('config')->get('database');
// [ 'host' => 'localhost', ... ]
```

However, if you just wanted to get, say, the `host` entry of the configuration array, you could use the following.

```php
Service::get('config')->get('database.host');
// 'localhost'
```

## Default Configuration

Configuration files for the Scaffold system are located at `system/config` and currently include the following systems.

 - `database`
 - `errors`

## Configuration Files

A configuration file should be named based on the system that it configures. For example, the coniguration file that contains database connection details is named `database.php`. All configuration options in this file will be automatically namespaced under this name. Configuration files should simply return an array that looks like the following.

```php
return [
    'environment' => [
        'key' => 'val',
        'another_key' => 'another_val'
    ],

    'another_environment' => [
        'another_key' => 'another_val'
    ]
];
```

There are two *special* entries in configuration arrays that could possibly be applied **regardless** of the current environment, and they're `global` and `default`.

All configuration arrays are merged with the `global` configuration option. There is no way to remove anything in the `global` configuration array without explicitly overwriting it, so you need to be careful what you put in it. 

The `default` configuration option is used if you do not have an environment set, or if there is no configuration array for the environment you are using. 