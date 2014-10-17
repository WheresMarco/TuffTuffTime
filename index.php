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
      $station = "Diö";
      $tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

      $departing = $tufftufftime->getDeparting();
      $arriving = $tufftufftime->getArriving();
      $stations = $tufftufftime->getStations();

      set_transient("tuffTuffTime_departing", $departing, 60*60*24);
      set_transient("tuffTuffTime_arriving", $arriving, 60*60*24);
      set_transient("tuffTuffTime_stations", $stations, 60*60*24);
    }

    /**
  	  * Outputs the content of the widget
  	  *
  	  * @param array $args
  	  * @param array $instance
  	  */
  	public function widget($args, $instance) {
      extract($args, EXTR_SKIP);
      $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
      $where = empty($instance['where']) ? 'departing' : $instance['where'];
      $number = empty($instance['number']) ? '5' : $instance['number'];

      // Before widget data
      echo (isset($before_widget)?$before_widget:'');

      if (!empty($title))
        echo $before_title . $title . $after_title;;

      if ($where === "departing") {
        // TODO Check if data exists or else get it
        $direction = get_transient("tuffTuffTime_departing");
        echo "<table><tr><th>Ankomst</th><th>Till</th><th>Spår</th><th>Tåg</th></tr>";
      } else {
        // TODO Check if data exists or else get it
        $direction = get_transient("tuffTuffTime_arriving");
        echo "<table><tr><th>Ankomst</th><th>Från</th><th>Spår</th><th>Tåg</th></tr>";
      }

      $stations = get_transient("tuffTuffTime_stations");

      $i = 0;
      foreach($direction['RESPONSE']['RESULT'][0]['TrainAnnouncement'] as $trainItem) {
        if ($i >= $number) {
          break;
        }

        $time = strtotime($trainItem['AdvertisedTimeAtLocation']);

        // Removes trains that have already past
        if($time <= (strtotime("-15 minutes") + 7200)) {
          continue;
        }

        echo "<tr>";
          echo "<td>". date("H:i", $time) . "</td>";

          if ($where === "departing") {
            foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
              if (array_search(end($trainItem['ToLocation']), $station)) {
                echo "<td>" . $station['AdvertisedLocationName'] . "</td>";
              }
            }
          } else {
            foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
              if (array_search($trainItem['FromLocation'][0], $station)) {
                echo "<td>" . $station['AdvertisedLocationName'] . "</td>";
              }
            }
          }

          echo "<td>" . $trainItem['TrackAtLocation'] . "</td>";
          echo "<td>" . $trainItem['AdvertisedTrainIdent'] . "</td>";
        echo "</tr>";

        $i++;
      }

      echo "</table>";

      // And after widget data
      echo (isset($after_widget)?$after_widget:'');
	  }

  	/**
  	  * Ouputs the options form on admin
  	  *
  	  * @param array $instance The widget options
  	  */
  	public function form($instance) {
      $instance = wp_parse_args((array) $instance, array('title' => 'TuffTuffTime', 'where' => 'departing', 'number' => '5'));
      $title = $instance['title'];
      $where = $instance['where'];
      $number = $instance['number'];

      ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                 name="<?php echo $this->get_field_name('title'); ?>" type="text"
                 value="<?php echo attribute_escape($title); ?>" />
        </label>
      </p>

      <p>
        <input type="radio" id="<?php echo $this->get_field_id('where'); ?>" name="<?php echo $this->get_field_name('where'); ?>" value="departing" <?php if(attribute_escape($where) == "departing") { echo "checked"; } ?> /> Avgående<br />
        <input type="radio" id="<?php echo $this->get_field_id('where'); ?>" name="<?php echo $this->get_field_name('where'); ?>" value="arriving" <?php if(attribute_escape($where) == "arriving") { echo "checked"; } ?> /> Ankommande<br />
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('number'); ?>">Antal att visa:
        <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>"
               name="<?php echo $this->get_field_name('number'); ?>" type="text"
               value="<?php echo attribute_escape($number); ?>" />
        </label>
      </p>
      <?php
  	}

  	/**
  	  * Processing widget options on save
  	  *
  	  * @param array $new_instance The new options
  	  * @param array $old_instance The previous options
  	  */
  	public function update($new_instance, $old_instance) {
  		$instance = $old_instance;
      $instance['title'] = $new_instance['title'];
      $instance['where'] = $new_instance['where'];
      $instance['number'] = $new_instance['number'];
      return $instance;
  	}

    /**
      * Shortcode - [tufftufftime where="departing" number="5"]
      */
    public static function shortcode($atts) {
      extract(shortcode_atts(array('where' => '', 'number' => ''), $atts));
      $where = empty($where) ? 'departing' : $where;
      $number = empty($number) ? '5' : $number;

      if ($where === "departing") {
        // TODO Check if data exists or else get it
        $direction = get_transient("tuffTuffTime_departing");
        echo "<table class='tufftufftime'><tr><th>Avgång</th><th>Till</th><th>Spår</th><th>Tåg</th></tr>";
      } else {
        // TODO Check if data exists or else get it
        $direction = get_transient("tuffTuffTime_arriving");
        echo "<table class='tufftufftime'><tr><th>Ankomst</th><th>Från</th><th>Spår</th><th>Tåg</th></tr>";
      }

      $stations = get_transient("tuffTuffTime_stations");

      $i = 0;
      foreach($direction['RESPONSE']['RESULT'][0]['TrainAnnouncement'] as $trainItem) {
        if ($i >= $number) {
          break;
        }

        $time = strtotime($trainItem['AdvertisedTimeAtLocation']);

        // Removes trains that have already past
        if($time <= (strtotime("-15 minutes") + 7200)) {
          continue;
        }

        echo "<tr>";
          echo "<td>". date("H:i", $time) . "</td>";

          if ($where === "departing") {
            foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
              if (array_search(end($trainItem['ToLocation']), $station)) {
                echo "<td>" . $station['AdvertisedLocationName'] . "</td>";
              }
            }
          } else {
            foreach($stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station) {
              if (array_search($trainItem['FromLocation'][0], $station)) {
                echo "<td>" . $station['AdvertisedLocationName'] . "</td>";
              }
            }
          }

          echo "<td>" . $trainItem['TrackAtLocation'] . "</td>";
          echo "<td>" . $trainItem['AdvertisedTrainIdent'] . "</td>";
        echo "</tr>";

        $i++;
      }

      echo "</table>";
    }
  }

  // Hooks
  register_activation_hook( __FILE__, array('tuffTuffTime', 'install'));
  register_deactivation_hook( __FILE__, array('tuffTuffTime', 'uninstall'));
  add_action('tuffTuffTime_download', array('tuffTuffTime', 'getData'));
  add_action('widgets_init', create_function('', 'return register_widget("tuffTuffTime");'));
  add_shortcode('tufftufftime', array('tuffTuffTime', 'shortcode'));
