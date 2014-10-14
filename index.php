<?php
  require_once("classes/TuffTuffTime.php");

  $station = "Kalmar C";

  $tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

  // Get departing as array
  echo $tufftufftime->getDeparting();

  echo "<br><br>";

  // Get arriving as array
  echo $tufftufftime->getArriving();
