<?php
  /*
  Plugin Name: TuffTuffTime
  Plugin URI: https://github.com/WheresMarco/TuffTuffTime
  Description: WordPress-widget that shows the current and future trainstops at a station.
  Author: Marco Hyyryläinen
  Version: 0.2-beta
  Author URI: http://wheresmar.co
  */

  // Some hard defines
  define('TUFFTUFFTIME_PATH', plugin_dir_path(__FILE__));

  require_once("classes/TuffTuffTime.php");

  // Main class for everything
  class tuffTuffTime extends WP_Widget {
    public function __construct() {
      $widget_ops = array('classname' => 'tuffTuffTime', 'description' => 'Displays the timetable.' );
      $this->WP_Widget('tuffTuffTime', 'TuffTuffTime', $widget_ops);
    }

    public static function install() {
      // Add a scheduled event for WordPress
      wp_schedule_event(time(), 'twicedaily', 'tuffTuffTime_download');
    }

    public static function uninstall() {
      // Remove the scheduled event
      wp_clear_scheduled_hook('tuffTuffTime_download');
    }

    public static function getData() {
      $station = "Kalmar C";
      $tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

      $departing = $tufftufftime->getDeparting();
      $arriving = $tufftufftime->getArriving();

      set_transient("tuffTuffTime_departing", $departing, 60*60*24);
      set_transient("tuffTuffTime_arriving", $arriving, 60*60*24);
    }

    /**
  	  * Outputs the content of the widget
  	  *
  	  * @param array $args
  	  * @param array $instance
  	  */
  	public function widget($args, $instance) {
      $arriving = get_transient("tuffTuffTime_arriving");

      echo "<table><tr><th>Ankomst</th><th>Från</th><th>Spår</th><th>Tåg</th><th></th><th>Anmärkning</th></tr>";

        foreach($arriving['RESPONSE']['RESULT'][0]['TrainAnnouncement'] as $arrivingItem) {
          $time = strtotime($arrivingItem['AdvertisedTimeAtLocation']);

          // TODO Fix this
          if($time <= strtotime("-15 minutes")) {
            continue;
          }

          echo "<tr>";
            echo "<td>". date("H:i", $time) . "</td>";
            echo "<td>" . $arrivingItem['FromLocation'][0] . "</td>";
            echo "<td>" . $arrivingItem['TrackAtLocation'] . "</td>";
            echo "<td>" . $arrivingItem['AdvertisedTrainIdent'] . "</td>";
            echo "<td>" . $arrivingItem['ProductInformation'][0] . "</td>";
            //echo "<td>" . $arrivingItem['OtherInformation'][0] . "</td>";
          echo "</tr>";
        }

      echo "</table>";
  	}

  	/**
  	  * Ouputs the options form on admin
  	  *
  	  * @param array $instance The widget options
  	  */
  	public function form($instance) {
  		// outputs the options form on admin
  	}

  	/**
  	  * Processing widget options on save
  	  *
  	  * @param array $new_instance The new options
  	  * @param array $old_instance The previous options
  	  */
  	public function update($new_instance, $old_instance) {
  		// processes widget options to be saved
  	}
  }

  // Hooks
  register_activation_hook( __FILE__, array('tuffTuffTime', 'install'));
  register_deactivation_hook( __FILE__, array('tuffTuffTime', 'uninstall'));
  add_action('tuffTuffTime_download', array('tuffTuffTime', 'getData'));
  add_action( 'widgets_init', create_function('', 'return register_widget("tuffTuffTime");') );
