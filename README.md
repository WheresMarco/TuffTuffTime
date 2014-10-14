# TuffTuffTime
A WordPress widget that shows the current and future trainstops at a station. You can strip the class out and use only that if you don't like WordPress.

TuffTuffTime is tested on `PHP 5.5.14` and requires `cURL`. Released under the [MIT license](LICENSE).

## Requirements
* You need to generate a API-key from [Trafiklab](http://www.trafiklab.se/) and
input it into `Settings.php` (rename `Settings-sample.php`).

## Usage of class
```php
// Require the class
require_once("classes/TuffTuffTime.php");

// Create a new TuffTuffTime-object with the station 
// that you want the information for.
$station = "Kalmar C";
$tufftufftime = new \TuffTuffTime\TuffTuffTime($station);

// Functions to use
$tufftufftime->getDeparting();
$tufftufftime->getArriving();
$tufftufftime->getStationName("Bg");
```

## Todo
* Make WordPress-plugin.
* Move API-key input somewhere else (will probably be in WP plugin settingspage).
* Get timetable for a train (based on train id).
* Get a stations messages - delays, cancellations, etc. 
