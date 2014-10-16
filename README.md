# TuffTuffTime
A WordPress widget that shows the current and future stops at a train station in Sweden. You can strip the class out and use only that if you don't like WordPress.

TuffTuffTime is tested on `PHP 5.5.14` and requires `cURL`. Released under the [MIT license](LICENSE).

## Requirements
You need to generate a API-key from [Trafiklab](http://www.trafiklab.se/api/trafikverket-oppet-api) and
create a `Settings.php` file that contains:

```php
<?php
  class Settings {
    public static $apiKey = "XXX";
  }
?>
```


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
