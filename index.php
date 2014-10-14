<!DOCTYPE html>
<html lang="se">
<head>
  <meta charset="utf-8">
</head>
<body>
  <?php
    // Example of working with the data
    require_once("classes/TuffTuffTime.php");

    $station = "Kalmar C";
    $tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

    $arriving = $tufftufftime->getArriving();
    foreach($arriving['RESPONSE']['RESULT'][0]['TrainAnnouncement'] as $arrivingItem) {
      echo "Ett " . $arrivingItem['TypeOfTraffic'] . " från " . $arrivingItem['InformationOwner'] .
        " ankommer kl: ". $arrivingItem['AdvertisedTimeAtLocation'] . " från " .
        $tufftufftime->getStationName($arrivingItem['FromLocation'][0]) . "<br>";
    }
  ?>
</body>
</html>
