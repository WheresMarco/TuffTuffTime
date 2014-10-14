<?php
  require_once("classes/TuffTuffTime.php");

  $station = "Kalmar C";
  $when = "today";

  $tufftufftime = new \TuffTuffTime\TuffTuffTime($station, $when);

  echo $tufftufftime->getDeparting();
