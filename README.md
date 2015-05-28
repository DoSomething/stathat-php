# StatHat [![Packagist](https://img.shields.io/packagist/v/dosomething/stathat.svg)](https://packagist.org/packages/dosomething/stathat)
This is a simple, modern API wrapper for [StatHat](https://www.stathat.com). It also includes
optional support for usage as a service provider & facade in Laravel 4 or 5.

### Installation
Install with Composer:
```json
"require": {
    "dosomething/stathat": "~1.0.0"
}
```

### Usage
In vanilla PHP, simply require the `Client` class and create a new instance with your credentials.
```php
  use DoSomething\StatHat\Client as StatHat;
  
  $stathat = new StatHat([
    'enabled' => (getenv('APP_ENV') == 'production'),
    'ez_key' => '...',
    'user_key' => '...'
  ]);
  $stathat->ezCount('stat_name', 1);
```

### Laravel Usage
Laravel support is built-in. Simply add a service provider & facade alias to your `config/app.php`:

```php
  'providers' => [
    // ...
    'DoSomething\StatHat\StatHatServiceProvider'
  ],
  
  'aliases' => [
    // ...
    'StatHat' => 'DoSomething\StatHat\Facade'
  ]
```

Finally, add your keys to the `config/services.php` configuration array:

```php
  'stathat' => [
    'ez_key' => 'your_ez_key@example.com' // required for EZ API
    'user_key' => '<Your_User_Key>' // required for Classic API
  ]
```

The `StatHat` facade will now be accessible from anywhere in your application:
```php
  StatHat::ezCount('stat_name', 1);
```

### License
&copy;2015 DoSomething.org. StatHat-PHP is free software, and may be redistributed under the terms specified in the [LICENSE](https://github.com/DoSomething/stathat-php/blob/master/LICENSE) file.
