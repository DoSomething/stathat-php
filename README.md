# StatHat
This is a simple, modern API wrapper for [StatHat](https://www.stathat.com). It also includes
optional support for usage as a Laravel 4/5 service provider and facade.

### Installation
Install with Composer:
```
"require": {
    "dosomething/stathat": "dev-master"
}
```

And then, if using Laravel (not required), add the service provider to `config/app.php` (or
`app/config/app.php` in Laravel 4) in the `providers` array.
```
  'DoSomething\StatHat\StatHatServiceProvider'
```

To use the `StatHat` facade, add it to the `aliases` array below:
```
  'StatHat' => 'DoSomething\StatHat\Facade'
```

Finally, add your keys to the `services.php` configuration array:
```
    'stathat' => [
        'ez_key' => 'your_ez_key@example.com' // required for EZ API
        'user_key' => '<Your_User_Key' // required for Classic API
    ],
```


### Usage
In vanilla PHP:
```php
  use DoSomething\StatHat\Client as StatHat;
  
  $stathat = new StatHat(['ez_key' => '...', 'user_key' => '...']);
  $stathat->ezCount('stat_name', 1);

```

In Laravel:
```php
  StatHat::ezCount('stat_name', 1);

```

### License
&copy;2015 DoSomething.org. StatHat-PHP is free software, and may be redistributed under the terms specified in the [LICENSE](blob/dev/LICENSE.md) file.
