<?php namespace DoSomething\StatHat;

use GuzzleHttp\Client as GuzzleClient;
use Exception;

class Client {

    /**
     * Configuration passed to the client.
     * @var array
     */
    protected $config;

    protected $client;
    /**
     * The base StatHat API URL.
     * @var string
     */
    protected $url = 'https://api.stathat.com';

    /**
     * Create a new StatHat client.
     * @param array $config
     */
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
     *
     * @see https://www.stathat.com/manual/send#classic
     * @param string $stat_key - Private key identifying the stat
     * @param int $count - Number you want to count
     * @throws \Exception
     */
    public function count($stat_key, $count = 1)
    {
        if(!isset($this->config['user_key'])) throw new Exception('StatHat user key not set.');
        if($this->config['debug']) return;

        return $this->client->post('c', ['body' => ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'count' => $count]]);
    }

    /**
     * Send a value using the classic API.
     *
     * @see https://www.stathat.com/manual/send#classic
     * @param string $stat_key - Private key identifying the stat
     * @param int $value - Value you want to track
     * @throws \Exception
     */
    public function value($stat_key, $value)
    {
        if(!isset($this->config['user_key'])) throw new Exception('StatHat user key not set.');
        if($this->config['debug']) return;

        return $this->client->post('v', ['body' => ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'value' => $value]]);
    }

    /**
     * Increment a counter using the EZ API.
     *
     * @see https://www.stathat.com/manual/send#ez
     * @param string $stat - Unique stat name
     * @param int $count - Number you want to count
     * @throws \Exception
     */
    public function ezCount($stat, $count = 1)
    {
        if(!isset($this->config['ez_key'])) throw new Exception('StatHat EZ key not set.');
        if($this->config['debug']) return;

        return $this->client->post('ez', ['body' => ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'count' => $count]]);
    }

    /**
     * Send a value using the EZ API.
     *
     * @see https://www.stathat.com/manual/send#ez
     * @param string $stat - Unique stat name
     * @param int $value - Value you want to track
     * @throws \Exception
     */
    public function ezValue($stat, $value)
    {
        if(!isset($this->config['ez_key'])) throw new Exception('StatHat EZ key not set.');
        if($this->config['debug']) return;

        return $this->client->post('ez', ['body' => ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'value' => $value]]);
    }

}
