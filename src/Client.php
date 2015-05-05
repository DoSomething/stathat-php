<?php namespace DoSomething\StatHat;

use GuzzleHttp\Client as GuzzleClient;
use Exception;

class Client {

    protected $config;

    protected $client;

    public function __construct($config = [])
    {
        $this->config = $config;

        $this->client = new GuzzleClient([
            'base_url' => 'https://api.stathat.com/',
            'defaults' => [
                'future' => true,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ],
        ]);
    }

    /**
     * Increment a counter using the classic API.
     * @see https://www.stathat.com/manual/send#classic
     *
     * @param $stat_key     string - Private key identifying the stat
     * @param $count        int - Number you want to count
     * @return \GuzzleHttp\Message\FutureResponse
     * @throws \Exception
     */
    public function count($stat_key, $count = 1)
    {
        if(!isset($this->config['user_key'])) throw new Exception('StatHat user key not set.');

        return $this->client->post('c', ['body' => ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'count' => $count]]);
    }

    /**
     * Send a value using the classic API.
     * @see https://www.stathat.com/manual/send#classic
     *
     * @param $stat_key     string - Private key identifying the stat
     * @param $value        int - Value you want to track
     * @return \GuzzleHttp\Message\FutureResponse
     * @throws \Exception
     */
    public function value($stat_key, $value)
    {
        if(!isset($this->config['user_key'])) throw new Exception('StatHat user key not set.');

        return $this->client->post('v', ['body' => ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'value' => $value]]);
    }

    /**
     * Increment a counter using the EZ API.
     * @see https://www.stathat.com/manual/send#ez
     *
     * @param $stat         string - Unique stat name
     * @param $count        int - Number you want to count
     * @return \GuzzleHttp\Message\FutureResponse
     * @throws \Exception
     */
    public function ezCount($stat, $count = 1)
    {
        if(!isset($this->config['ez_key'])) throw new Exception('StatHat EZ key not set.');

        return $this->client->post('ez', ['body' => ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'count' => $count]]);
    }

    /**
     * Send a value using the EZ API.
     * @see https://www.stathat.com/manual/send#ez
     *
     * @param $stat         string - Unique stat name
     * @param $value        int - Value you want to track
     * @return \GuzzleHttp\Message\FutureResponse
     * @throws \Exception
     */
    public function ezValue($stat, $value)
    {
        if(!isset($this->config['ez_key'])) throw new Exception('StatHat EZ key not set.');

        return $this->client->post('ez', ['body' => ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'value' => $value]]);
    }

}
