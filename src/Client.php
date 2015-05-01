<?php namespace DoSomething\StatHat;

use GuzzleHttp\Client as GuzzleClient;

class Client {

  protected $client;

  public function __construct($key = NULL)
  {
    $this->client = new GuzzleClient([
      'base_url' => 'https://api.stathat.com/',
      'defaults' => [
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded',
          'Connection' => 'Close'
        ]
      ],
    ]);
  }

  /**
   * Increment a counter using the classic API.
   * @see https://www.stathat.com/manual/send#classic
   *
   * @param $stathat_key  string - Private key identifying the stat
   * @param $user_key     string - Private key identifying the user
   * @param $count        int - Number you want to count
   */
  public function count($stathat_key, $user_key, $count = 1)
  {
    $this->client->post('c', ['body' => ['key' => $stathat_key, 'ukey' => $user_key, 'count' => $count]]);
  }

  /**
   * Send a value using the classic API.
   * @see https://www.stathat.com/manual/send#classic
   *
   * @param $stathat_key  string - Private key identifying the stat
   * @param $user_key     string - Private key identifying the user
   * @param $value        string - Value you want to track
   */
  public function value($stathat_key, $user_key, $value)
  {
    $this->client->post('v', ['body' => ['key' => $stathat_key, 'ukey' => $user_key, 'value' => $value]]);
  }

  /**
   * Increment a counter using the EZ API.
   * @see https://www.stathat.com/manual/send#ez
   *
   * @param $ez_key       string - EZ Key (defaults to email address)
   * @param $stat         string - Unique stat name
   * @param $count        int - Number you want to count
   */
  public function ezCount($ez_key, $stat, $count = 1)
  {
    $this->client->post('ez', ['body' => ['ezkey' => $ez_key, 'stat' => $stat, 'count' => $count]]);
  }

  /**
   * Send a value using the EZ API.
   * @see https://www.stathat.com/manual/send#ez
   *
   * @param $ez_key
   * @param $stat
   * @param $value
   */
  public function ezValue($ez_key, $stat, $value)
  {
    $this->client->post('ez', ['body' => ['ezkey' => $ez_key, 'stat' => $stat, 'value' => $value]]);
  }

}