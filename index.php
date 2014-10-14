<?php
  require_once("classes/TuffTuffTime.php");

  $station = "Kalmar C";

  $tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

  echo $tufftufftime->getDeparting();
  echo "<br><br>";
  echo $tufftufftime->getArriving();
