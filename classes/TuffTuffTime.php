<?php
  namespace TuffTuffTime;

  class TuffTuffTime {
    private $stationID;
    private $when;
    private static $apiURL = "http://api.tagtider.net/v1/";
    private static $apiIdentifier = "TuffTuffTime";
    private static $apiUser = "tagtider";
    private static $apiPassword = "codemocracy";

    public function __construct($station, $when = "today") {
      $this->stationID = $this->getStationID($station);
      $this->when = $when;
    }

    public function getDeparting() {
      // Setup request
      $options = array(
          CURLOPT_FRESH_CONNECT   => 1,
          CURLOPT_URL             => self::$apiURL . "/stations/" . $this->stationID . "/transfers/departures.json",
          CURLOPT_USERAGENT       => self::$apiIdentifier,
          CURLOPT_HTTPAUTH        => CURLAUTH_DIGEST,
          CURLOPT_USERPWD         => self::$apiUser . ":" . self::$apiPassword,
          CURLOPT_RETURNTRANSFER  => 3,
      );

      // Handle and return
      $session = curl_init();
      curl_setopt_array($session, $options);
      $response = curl_exec($session);
      curl_close($session);

      if(!$response)
        throw new \Exception("Could not get departures");

      $responseArray = json_decode($response, true);

      var_dump($responseArray);

      return $this->stationID;
    }

    private function getStationID($name) {
      // Setup request
      $options = array(
          CURLOPT_FRESH_CONNECT   => 1,
          CURLOPT_URL             => self::$apiURL . "stations.json",
          CURLOPT_USERAGENT       => self::$apiIdentifier,
          CURLOPT_HTTPAUTH        => CURLAUTH_DIGEST,
          CURLOPT_USERPWD         => self::$apiUser . ":" . self::$apiPassword,
          CURLOPT_RETURNTRANSFER  => 3,
      );

      // Handle and return
      $session = curl_init();
      curl_setopt_array($session, $options);
      $response = curl_exec($session);
      curl_close($session);

      if(!$response)
        throw new \Exception("Could not get stations.");

      $responseArray = json_decode($response, true);
      $foundID = 0;

      // Loop through the returned array to find the id
      foreach($responseArray['stations']['station'] as $station) {
        if (array_search($name, $station)) {
          $foundID = $station['id'];
        }
      }

      if ($foundID === 0) {
        throw new \Exception("Could not find ID in returned array.");
      }

      return $foundID;
    }
  }
