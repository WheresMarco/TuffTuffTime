<?php
  namespace TuffTuffTime;

  require_once("Settings.php");

  class TuffTuffTime {
    private $stationID;
    private $when;
    private static $apiURL = "http://api.trafikinfo.trafikverket.se/v1/data.json";

    public function __construct($station, $when = "today") {
      $this->stationID = $this->getStationID($station);
      $this->when = $when;
    }

    public function getDeparting() {
      return $this->stationID;
    }

    private function getStationID($name) {
      $options = array(
          CURLOPT_FRESH_CONNECT   => 1,
          CURLOPT_URL             => self::$apiURL,
          CURLOPT_RETURNTRANSFER  => 3,
          CURLOPT_POST            => 1,
          CURLOPT_HTTPHEADER      => array('Content-Type: text/xml')
      );

      $xml = "<REQUEST>" .
                "<LOGIN authenticationkey='". \Settings::$apiKey ."' />" .
                "<QUERY objecttype='TrainStation'>" .
                  "<FILTER/>" .
                  "<INCLUDE>AdvertisedLocationName</INCLUDE>" .
                  "<INCLUDE>LocationSignature</INCLUDE>" .
                "</QUERY>" .
              "</REQUEST>";

      // Open up curl session and fire of the request
      $session = curl_init();
      curl_setopt_array($session, $options);
      curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
      $response = curl_exec($session);
      curl_close($session);

      // Check if we got a response
      if(!$response)
        throw new \Exception("Could not get departures");

      // Decode the response to json
      $responseArray = json_decode($response, true);
      $foundID = "";

      // // Loop through the returned array to find the id
      foreach($responseArray['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
        if (array_search($name, $station)) {
          $foundID = $station['LocationSignature'];
        }
      }

      // No match? Throw a exception
      if ($foundID === "") {
        throw new \Exception("Could not find ID in returned array. Must be name exact name (ex. Stockholm C)");
      }

      return $foundID;
    }
  }
