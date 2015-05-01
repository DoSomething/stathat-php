<?php namespace DoSomething\StatHat;

use Illuminate\Support\Facades\Facade;

class StatHat extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'stathat';
  }
}