<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/public/partials
 */

 $returnHTML = '';

  if ( $attributes['type'] === 'arriving' ) :
    $returnHTML .= '<table class="TuffTuffTime"><tr><th>' . __( 'Arriving', 'TuffTuffTime' ) . '</th><th>' . __( 'From', 'TuffTuffTime' ) . '</th><th>' . __( 'Track', 'TuffTuffTime' ) . '</th><th>' . __( 'Train', 'TuffTuffTime' ) . '</th></tr>';
  else :
     $returnHTML .= '<table class="TuffTuffTime"><tr><th>' . __( 'Departing', 'TuffTuffTime' ) . '</th><th>' . __( 'To', 'TuffTuffTime' ) . '</th><th>' . __( 'Track', 'TuffTuffTime' ) . '</th><th>' . __( 'Train', 'TuffTuffTime' ) . '</th></tr>';
  endif;

    for ( $i = 0; $i < (int)$attributes['limit']; $i++ ) {
      $item = $data['RESPONSE']['RESULT'][0]['TrainAnnouncement'][$i];

      $time = strtotime($item['AdvertisedTimeAtLocation']);

      // Removes trains that have already past
      if($time <= (strtotime("-15 minutes") + 7200)) {
        continue;
      }

      $returnHTML .= "<tr>";
        $returnHTML .= "<td>". date("H:i", $time) . "</td>";
          if ( $attributes['type'] === 'arriving' ) :
            $location = $item['FromLocation'][0];
          else :
            $location = array_pop((array_slice($item['ToLocation'], -1)));
          endif;

          foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
            if (array_search($location, $station)) {
              $returnHTML .= "<td>" . $station['AdvertisedLocationName'] . "</td>";
            }
          }

        $returnHTML .= "<td>" . $item['TrackAtLocation'] . "</td>";
        $returnHTML .= "<td>" . $item['AdvertisedTrainIdent'] . "</td>";
      $returnHTML .= "</tr>";
    }
  $returnHTML .= "</table>";

  return $returnHTML;
?>
