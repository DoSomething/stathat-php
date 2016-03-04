# StatHat [![Packagist](https://img.shields.io/packagist/v/dosomething/stathat.svg)](https://packagist.org/packages/dosomething/stathat)
This is a simple, modern API wrapper for [StatHat](https://www.stathat.com). It also includes
optional support for usage as a service provider & facade in Laravel 4 or 5.

### Installation
Install with Composer:
```json
"require": {
    "dosomething/stathat": "^1.1.0"
}
```

### Usage
In vanilla PHP, require the `Client` class and create a new instance with your credentials.
```php
  use DoSomething\StatHat\Client as StatHat;
  
  $stathat = new StatHat([
    'user_key' => '<your_user_key>',       // required for count() and value()
    'ez_key' => 'your_ez_key@example.com', // required for ezCount() and ezValue()
    'debug' => false,                      // optional! will prevent sending stats if true.
  ]);
  
  // And go!
  $stathat->ezCount('<stat_name>', 1);
  $stathat->ezValue('<stat_name>', 15);
  
  $stathat->count('<stat_key>', 1);
  $stathat->value('<stat_key>', 9);
```

### Laravel Usage
Laravel support is built-in. Simply add a service provider & facade alias to your `config/app.php`:

```php
  'providers' => [
    // ...
    DoSomething\StatHat\StatHatServiceProvider::class,
  ],
  
  'aliases' => [
    // ...
    'StatHat' => DoSomething\StatHat\Facades\StatHat::class
  ],
```

Finally, add your keys to the `config/services.php` configuration array:

```php
  'stathat' => [
    'user_key' => '<your_user_key>',       // required for count() and value()
    'ez_key' => 'your_ez_key@example.com', // required for ezCount() and ezValue()
    'debug' => env('APP_DEBUG'),        // optional! will prevent sending stats in debug mode.
  ],
```

The `StatHat` facade will now be accessible from anywhere in your application:
```php
  StatHat::ezCount('<stat_name>', 1);
  StatHat::ezValue('<stat_name>', 15);
  
  StatHat::count('stat_key', 1);
  StatHat::value('stat_key', 9);
```

### License
&copy;2016 DoSomething.org. StatHat-PHP is free software, and may be redistributed under the terms specified in the [LICENSE](https://github.com/DoSomething/stathat-php/blob/master/LICENSE) file.  The name and logo for DoSomething.org are trademarks of Do Something, Inc and may not be used without permission.
