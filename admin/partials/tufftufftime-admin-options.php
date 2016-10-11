<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/admin/partials
 */
?>

<div class="wrap">
  <h1><?= esc_html(get_admin_page_title()); ?></h1>
  <form action="options.php" method="post">
      <?php
      // output security fields for the registered setting "wporg"
      settings_fields('tufftufftime');
      // output setting sections and their fields
      // (sections are registered for "wporg", each field is registered to a specific section)
      do_settings_sections('tufftufftime');

      // output save settings button
      submit_button('Save Settings');

      echo '<h2>' . __( 'All stations', 'TuffTuffTime' ) . '</h2>';
      echo '<p>' . __( 'To make it easier then to just guess how to spell a station we made a list with every one in the Trafikverket API. The spelling must be exactly the same as in in the list.<br /><i>Hint: Use the browsers built in search to filter the list (CMD + F, CTRL + F).</i>', 'TuffTuffTime' ) . '</p>';
      echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead>';
        echo '<tr>';
          echo '<td>Stationsnamn</td>';
          echo '<td>Förkortning</td>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';
          foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) :
            echo '<tr>';
              echo '<td>' . $station['AdvertisedLocationName'] . '</td>';
              echo '<td>' . $station['LocationSignature'] . '</td>';
            echo '</tr>';
          endforeach;
        echo '</tbody>';
      echo '</table>';
      ?>
  </form>
</div>
