Pico GAnalytics
===============

Adds automatically the Google Analytics tracking script to the head section of each page of the site.

## Installation

Place `pico_ganalytics.php` file into the `plugins` directory.

## Configuration

Inside the `config.php` file:

````
/* Google Analytics */
// The Google Analytics ID for your site:
// Admin --> Property --> Property Settings
$config['GAnalytics']['trackingID'] = 'UA-53343030-1';
/* Basic options */
// Demographics and Interest Reports
// (activated in Admin --> Property --> Property Settings)
$config['GAnalytics']['demoint'] = true;
//Enhanced link attribution
// (activated in Admin --> Property --> Property Settings)
$config['GAnalytics']['linkatt'] = true;
````

## TODO

- adding personal tracking variables, goals... ?