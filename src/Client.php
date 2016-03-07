<?php namespace DoSomething\StatHat;

use GuzzleHttp\Client as GuzzleClient;
use Exception;

class Client {

    /**
     * Configuration passed to the client.
     * @var array
     */
    protected $config;

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
        $this->post('c', ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'count' => $count]);
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
        $this->post('v', ['ukey' => $this->config['user_key'], 'key' => $stat_key, 'value' => $value]);
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

        // If a prefix is set, prepend it to the stat name.
        if(! empty($this->config['prefix'])) {
            $stat = $this->config['prefix'].$stat;
        }

        $this->post('ez', ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'count' => $count]);
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

        // If a prefix is set, prepend it to the stat name.
        if(! empty($this->config['prefix'])) {
            $stat = $this->config['prefix'].$stat;
        }

        $this->post('ez', ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'value' => $value]);
    }

    /**
     * Perform an asynchronous POST request to the given route.
     *
     * @param string $route
     * @param array $body
     */
    private function post($route = '', array $body = [])
    {
        // Don't send requests in debug mode.
        if($this->config['debug']) return;

        $contents = http_build_query($body);
        $parts = parse_url($this->url.'/'.$route);

        $err_num = null;
        $err_str = null;
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $err_num, $err_str, 30);

        // Y'know back in my day we had to write our HTTP requests by
        // hand, and uphill both ways. Now these kids with their Guzzles...
        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($contents)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($contents)) $out.= $contents;

        // Fly away, little packet!
        fwrite($fp, $out);
        fclose($fp);
    }
}
