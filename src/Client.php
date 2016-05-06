<?php

namespace DoSomething\StatHat;

use Exception;

class Client
{
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
    protected $alerts_url = 'https://www.stathat.com';

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
        if (! isset($this->config['user_key'])) {
            throw new Exception('StatHat user key not set.');
        }

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
        if (! isset($this->config['user_key'])) {
            throw new Exception('StatHat user key not set.');
        }

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
        if (! isset($this->config['ez_key'])) {
            throw new Exception('StatHat EZ key not set.');
        }

        // If a prefix is set, prepend it to the stat name.
        if (! empty($this->config['prefix'])) {
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
        if (! isset($this->config['ez_key'])) {
            throw new Exception('StatHat EZ key not set.');
        }

        // If a prefix is set, prepend it to the stat name.
        if (! empty($this->config['prefix'])) {
            $stat = $this->config['prefix'].$stat;
        }

        $this->post('ez', ['ezkey' => $this->config['ez_key'], 'stat' => $stat, 'value' => $value]);
    }

    /**
     * List stat alerts using the Alerts API. Used to look up alert IDs of alerts assigned
     * to certain stats.
     *
     * @see https://www.stathat.com/manual/alerts_api
     * @throws \Exception
     */
    public function listAlerts()
    {
        if (! isset($this->config['access_token'])) {
            throw new Exception('StatHat Alerts API Access Token not set.');
        }

        $result = $this->deleteAlertCurl('x/'.$this->config['access_token'].'/alerts/'.$alert_id);
        return $result;
    }
    
    /**
     * Get listing of all stat alerts using the Alerts API.
     *
     * @see https://www.stathat.com/manual/alerts_api
     * @throws \Exception
     */
    public function getAlerts()
    {
        if (! isset($this->config['access_token'])) {
            throw new Exception('StatHat Alerts API Access Token not set.');
        }
        $result = $this->curlGet($this->alerts_url.'/x/'.$this->config['access_token'].'/alerts');

        return $result;
    }

    /**
     * Delete a stat alert using the Alerts API.
     *
     * @see https://www.stathat.com/manual/alerts_api
     * @param string $alert_id - Unique id of alert to be deleted
     * @throws \Exception
     */
    public function deleteAlert($alert_id)
    {
        if (! isset($this->config['access_token'])) {
            throw new Exception('StatHat Alerts API Access Token not set.');
        }
        $result = $this->curlDelete($this->alerts_url.'/x/'.$this->config['access_token'].'/alerts/'.$alert_id);
        
        return $result;
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
        if ($this->config['debug']) {
            return;
        }

        $contents = http_build_query($body);
        $parts = parse_url($this->url.'/'.$route);

        $err_num = null;
        $err_str = null;
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $err_num, $err_str, 30);

        // Y'know back in my day we had to write our HTTP requests by
        // hand, and uphill both ways. Now these kids with their Guzzles...
        $out = 'POST '.$parts['path'].' HTTP/1.1'."\r\n";
        $out .= 'Host: '.$parts['host']."\r\n";
        $out .= 'Content-Type: application/x-www-form-urlencoded'."\r\n";
        $out .= 'Content-Length: '.strlen($contents)."\r\n";
        $out .= 'Connection: Close'."\r\n\r\n";
        if (isset($contents)) {
            $out .= $contents;
        }

        // Fly away, little packet!
        fwrite($fp, $out);
        fclose($fp);
    }

    /**
     * Perform cURL GET request to route. For example used by
     * Alerts API requests: https://www.stathat.com/manual/alerts_api
     *
     * @param string $curlURL
     *   Ex: https://www.stathat.com/x/ACCESSTOKEN/alerts
     * @return array or string $result
     *   An array of alert details or the response code string in the case of a non 200 response.
     */
    private function curlGet($curlURL)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jsonResult = curl_exec($ch);
        $result = json_decode($jsonResult);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($responseCode != 200) {
          $result = $responseCode;
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Perform cURL DELETE request to route. For example used by
     * Alerts API requests: https://www.stathat.com/manual/alerts_api
     *
     * @param string $curl_url
     *   Ex: /x/ACCESSTOKEN/alerts/ALERTID
     * @return string $result
     *   Response code.
     */
    private function curlDelete($curl_url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 404) {
          $result = $http_code;
        }
        else {
          $json_result = json_decode($result);
          $result = $http_code . ': ' . $json_result->msg;
        }

        return $result;
    }
}
