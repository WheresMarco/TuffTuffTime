<?php
  require_once("classes/TuffTuffTime.php");

  $station = "Arboga";
  $when = "today";

  $tufftufftime = new \TuffTuffTime\TuffTuffTime($station, $when);

  echo $tufftufftime->getDeparting();
