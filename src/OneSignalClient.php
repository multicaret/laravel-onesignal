<?php

namespace Liliom\OneSignal;

use GuzzleHttp\Client;

class OneSignalClient
{
    const API_URL = "https://onesignal.com/api/v1";
    const ENDPOINT_NOTIFICATIONS = "/notifications";
    const ENDPOINT_PLAYERS = "/players";

    private $client;
    private $headers;
    private $appId;
    private $restApiKey;
    private $userAuthKey;
    private $additionalParams;

    /**
     * @var bool
     */
    public $requestAsync = false;

    /**
     * @var Callable
     */
    private $requestCallback;

    /**
     * Turn on, turn off async requests
     *
     * @param bool $on
     *
     * @return $this
     */
    public function async($on = true)
    {
        $this->requestAsync = $on;

        return $this;
    }

    /**
     * Callback to execute after OneSignal returns the response
     *
     * @param Callable $requestCallback
     *
     * @return $this
     */
    public function callback(Callable $requestCallback)
    {
        $this->requestCallback = $requestCallback;

        return $this;
    }

    public function __construct($appId, $restApiKey, $userAuthKey)
    {
        $this->appId = $appId;
        $this->restApiKey = $restApiKey;
        $this->userAuthKey = $userAuthKey;

        $this->client = new Client();
        $this->headers = ['headers' => []];
        $this->additionalParams = [];
    }

    public function testCredentials()
    {
        return "APP ID: " . $this->appId . " REST: " . $this->restApiKey;
    }

    private function requiresAuth()
    {
        $this->headers['headers']['Authorization'] = 'Basic ' . $this->restApiKey;
    }

    private function usesJSON()
    {
        $this->headers['headers']['Content-Type'] = 'application/json';
    }

    public function addParams($params = [])
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function setParam($key, $value)
    {
        $this->additionalParams[$key] = $value;

        return $this;
    }

    public function sendNotificationToUser(
        $message,
        $userId,
        $url = null,
        $data = null,
        $buttons = null,
        $schedule = null
    ) {
        $contents = [
            "en" => $message
        ];

        $params = [
            'app_id' => $this->appId,
            'contents' => $contents,
            'include_player_ids' => [$userId]
        ];

        if (isset($url)) {
            $params['url'] = $url;
        }

        if (isset($data)) {
            $params['data'] = $data;
        }

        if (isset($buttons)) {
            $params['buttons'] = $buttons;
        }

        if (isset($schedule)) {
            $params['send_after'] = $schedule;
        }

        $this->sendNotificationCustom($params);
    }

    public function sendNotificationUsingTags(
        $message,
        $tags,
        $url = null,
        $data = null,
        $buttons = null,
        $schedule = null
    ) {
        $contents = [
            'en' => mb_convert_encoding($message, 'UTF-8', 'UTF-8')
        ];

        $params = [
            'app_id' => $this->appId,
            'contents' => $contents,
            'tags' => $tags,
        ];

        if (isset($url)) {
            $params['url'] = $url;
        }

        if (isset($data)) {
            $params['data'] = $data;
        }

        if (isset($buttons)) {
            $params['buttons'] = $buttons;
        }

        if (isset($schedule)) {
            $params['send_after'] = $schedule;
        }

        $this->sendNotificationCustom($params);
    }

    public function sendNotificationToAll($message, $url = null, $data = null, $buttons = null, $schedule = null)
    {
        $contents = [
            "en" => $message
        ];

        $params = [
            'app_id' => $this->appId,
            'contents' => $contents,
            'included_segments' => ['All']
        ];

        if (isset($url)) {
            $params['url'] = $url;
        }

        if (isset($data)) {
            $params['data'] = $data;
        }

        if (isset($buttons)) {
            $params['buttons'] = $buttons;
        }

        if (isset($schedule)) {
            $params['send_after'] = $schedule;
        }

        $this->sendNotificationCustom($params);
    }

    public function sendNotificationToSegment(
        $message,
        $segment,
        $url = null,
        $data = null,
        $buttons = null,
        $schedule = null
    ) {
        $contents = [
            "en" => $message
        ];

        $params = [
            'app_id' => $this->appId,
            'contents' => $contents,
            'included_segments' => [$segment]
        ];

        if (isset($url)) {
            $params['url'] = $url;
        }

        if (isset($data)) {
            $params['data'] = $data;
        }

        if (isset($buttons)) {
            $params['buttons'] = $buttons;
        }

        if (isset($schedule)) {
            $params['send_after'] = $schedule;
        }

        $this->sendNotificationCustom($params);
    }

    public function sendNotificationToUserByEmail(
        $message,
        $email,
        $filters = [],
        $segment = ['All'],
        $url = null,
        $data = null,
        $buttons = null,
        $schedule = null,
        $smallIcon = null,
        $LargeIcon = null,
        $bigPicture = null,
        $androidAccentCircleColor = null,
        $androidAccentLedColor = null,
        $sound = null
    ) {
        if (count($filters)) {
            $filters = array_merge($filters, ["field" => "email", "value" => $email]);
        } else {
            $filters = [["field" => "email", "value" => $email]];
        }

        return $this->sendNotificationToFiltersToSegment($message, $filters, $segment, $url, $data, $buttons, $schedule,
            $smallIcon, $LargeIcon, $bigPicture, $androidAccentCircleColor, $androidAccentLedColor, $sound);
    }

    public function sendNotificationToFiltersToSegment(
        $message,
        $filters,
        $segment = ['All'],
        $url = null,
        $data = null,
        $buttons = null,
        $schedule = null,
        $smallIcon = null,
        $LargeIcon = null,
        $bigPicture = null,
        $androidAccentCircleColor = null,
        $androidAccentLedColor = null,
        $sound = null
    ) {
        $contents = [
            "en" => $message
        ];

        $params = [
            'app_id' => $this->appId,
            'contents' => $contents,
            'filters' => $filters,
            'included_segments' => [$segment],
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1,
        ];

        if (isset($url)) {
            $params['url'] = $url;
        }

        if (isset($data)) {
            $params['data'] = $data;
        }

        if (isset($buttons)) {
            $params['buttons'] = $buttons;
        }

        if (isset($schedule)) {
            $params['send_after'] = $schedule;
        }

        if (isset($smallIcon)) {
            $params['small_icon'] = $smallIcon;
        }
        if (isset($LargeIcon)) {
            $params['large_icon'] = $LargeIcon;
        }
        if (isset($bigPicture)) {
            $params['big_picture'] = $bigPicture;
        }
        if (isset($androidAccentCircleColor)) {
            $params['android_accent_color'] = $androidAccentCircleColor;
        }
        if (isset($androidAccentLedColor)) {
            $params['android_led_color'] = $androidAccentLedColor;
        }
        if (isset($sound)) {
            $params['ios_sound'] = $sound . '.wav';
            $params['android_sound'] = $sound;
        }

        return $this->sendNotificationCustom($params);
    }

    /**
     * Send a notification with custom parameters defined in
     * https://documentation.onesignal.com/reference#section-example-code-create-notification
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function sendNotificationCustom($parameters = [])
    {
        $this->requiresAuth();
        $this->usesJSON();
        // By Sucipto
        if (isset($parameters['api_key'])) {
            $this->headers['headers']['Authorization'] = 'Basic ' . $parameters['api_key'];
        }
        // Make sure to use app_id
        if ( ! $parameters['app_id']) {
            $parameters['app_id'] = $this->appId;
        }

        // Make sure to use included_segments
        if (empty($parameters['included_segments']) && empty($parameters['include_player_ids'])) {
            $parameters['included_segments'] = ['all'];
        }
        $parameters = array_merge($parameters, $this->additionalParams);
//        \Log::alert('from OneSignal', $parameters);

        $this->headers['body'] = json_encode($parameters);
        $this->headers['buttons'] = json_encode($parameters);
        $this->headers['verify'] = false;

        return json_decode($this->post(self::ENDPOINT_NOTIFICATIONS)->getBody()->getContents());
    }

    /**
     * Creates a user/player
     *
     * @param array $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public function createPlayer(Array $parameters)
    {
        if ( ! isset($parameters['device_type']) or ! is_numeric($parameters['device_type'])) {
            throw new \Exception('The `device_type` param is required as integer to create a player(device)');
        }

        return $this->sendPlayer($parameters, 'POST', self::ENDPOINT_PLAYERS);
    }

    /**
     * Edit a user/player
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function editPlayer(Array $parameters)
    {
        return $this->sendPlayer($parameters, 'PUT', self::ENDPOINT_PLAYERS . '/' . $parameters['id']);
    }

    /**
     * Create or update a by $method value
     *
     * @param array $parameters
     * @param       $method
     * @param       $endpoint
     *
     * @return mixed
     */
    private function sendPlayer(Array $parameters, $method, $endpoint)
    {
        $this->requiresAuth();
        $this->usesJSON();

        $parameters['app_id'] = $this->appId;
        $this->headers['body'] = json_encode($parameters);

        $method = strtolower($method);

        return $this->{$method}($endpoint);
    }

    public function post($endPoint)
    {
        if ($this->requestAsync === true) {
            $promise = $this->client->postAsync(self::API_URL . $endPoint, $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->post(self::API_URL . $endPoint, $this->headers);
    }

    public function put($endPoint)
    {
        if ($this->requestAsync === true) {
            $promise = $this->client->putAsync(self::API_URL . $endPoint, $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->put(self::API_URL . $endPoint, $this->headers);
    }
}
