<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/public/partials
 */
?>

<h1>Simple timetable</h1>
<?php
  echo "<table class='tufftufftime'><tr><th>Ankomst</th><th>Från</th><th>Spår</th><th>Tåg</th></tr>";
  foreach ( $arriving['RESPONSE']['RESULT'][0]['TrainAnnouncement'] as $item ) :
    $time = strtotime($item['AdvertisedTimeAtLocation']);

        // Removes trains that have already past
        if($time <= (strtotime("-15 minutes") + 7200)) {
          continue;
        }

        echo "<tr>";
          echo "<td>". date("H:i", $time) . "</td>";

            foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
              if (array_search($item['FromLocation'][0], $station)) {
                echo "<td>" . $station['AdvertisedLocationName'] . "</td>";
              }
            }


          echo "<td>" . $item['TrackAtLocation'] . "</td>";
          echo "<td>" . $item['AdvertisedTrainIdent'] . "</td>";
        echo "</tr>";
  endforeach;
  echo "</table>";
?>
