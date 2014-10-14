<?php
  namespace TuffTuffTime;

  require_once("Settings.php");

  class TuffTuffTime {
    private $stations;
    private $stationID;
    private static $options = array(
        CURLOPT_FRESH_CONNECT   => 1,
        CURLOPT_URL             => "http://api.trafikinfo.trafikverket.se/v1/data.json",
        CURLOPT_RETURNTRANSFER  => 3,
        CURLOPT_POST            => 1,
        CURLOPT_HTTPHEADER      => array('Content-Type: text/xml')
    );

    public function __construct($station) {
      $this->stations = $this->getStations();
      $this->stationID = $this->getStationID($station);
    }

    /**
      * Get arriving trains/busses(?) to the station
      *
      * @return array
      */
    public function getArriving() {
      $xml = "<REQUEST>" .
              "<LOGIN authenticationkey='". \Settings::$apiKey ."' />" .
              "<QUERY objecttype='TrainAnnouncement' orderby='AdvertisedTimeAtLocation'>" .
                "<FILTER>" .
                  "<AND>" .
                  "<EQ name='ActivityType' value='Avgang' />" .
                  "<EQ name='LocationSignature' value='" . $this->stationID . "' />" .
                  "<OR>" .
                      "<AND>" .
                        "<GT name='AdvertisedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                        "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(14:00:00)' />" .
                      "</AND>" .
                      "<AND>" .
                        "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(00:30:00)' />" .
                        "<GT name='EstimatedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                      "</AND>" .
                    "</OR>" .
                  "</AND>" .
                "</FILTER>" .
              "</QUERY>" .
              "</REQUEST>";

      // Open up curl session and fire of the request
      $session = curl_init();
      curl_setopt_array($session, self::$options);
      curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
      $response = curl_exec($session);
      curl_close($session);

      // Check if we got a response
      if(!$response)
        throw new \Exception("Could not get stations");

      return $response;
    }

    /**
      * Get departing trains/busses(?) to the station
      *
      * @return array
      */
    public function getDeparting() {
      $xml = "<REQUEST>" .
              "<LOGIN authenticationkey='". \Settings::$apiKey ."' />" .
              "<QUERY objecttype='TrainAnnouncement' orderby='AdvertisedTimeAtLocation'>" .
                "<FILTER>" .
                  "<AND>" .
                  "<EQ name='ActivityType' value='Ankomst' />" .
                  "<EQ name='LocationSignature' value='" . $this->stationID . "' />" .
                  "<OR>" .
                      "<AND>" .
                        "<GT name='AdvertisedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                        "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(14:00:00)' />" .
                      "</AND>" .
                      "<AND>" .
                        "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(00:30:00)' />" .
                        "<GT name='EstimatedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                      "</AND>" .
                    "</OR>" .
                  "</AND>" .
                "</FILTER>" .
              "</QUERY>" .
              "</REQUEST>";

      // Open up curl session and fire of the request
      $session = curl_init();
      curl_setopt_array($session, self::$options);
      curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
      $response = curl_exec($session);
      curl_close($session);

      // Check if we got a response
      if(!$response)
        throw new \Exception("Could not get stations");

      return $response;
    }

    /**
      * Retrives the stations from the api
      *
      * @return json-array
      */
    public function getStations() {
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
      curl_setopt_array($session, self::$options);
      curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
      $response = curl_exec($session);
      curl_close($session);

      // Check if we got a response
      if(!$response)
        throw new \Exception("Could not get stations");


      return json_decode($response, true);
    }

    /**
      * Get the stationID for a station. Pass in the name.
      *
      * @return string
      */
    private function getStationID($name) {
      $foundID = "";

      // // Loop through the returned array to find the id
      foreach($this->stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
        if (array_search($name, $station)) {
          $foundID = $station['LocationSignature'];
        }
      }

      // No match? Throw a exception
      if ($foundID === "")
        throw new \Exception("Could not find ID in returned array. Must be name exact name (ex. Stockholm C)");

      return $foundID;
    }
  }
