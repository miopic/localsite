<?php
	define('ZONE1',0);
	define('ZONE2',96);
	define('ZONE3',112);
	define('ZONE4',129);
	define('ZONE5',145);

	define('MY_EPOCH_BIRTHDAY', -315548000);
	define('APP_NAME','/app');
	define('TOKEN_FILE', 'token.ini');
	define("B","<br>");
	define('SP', '&nbsp;');
	define("DS","/");
	define("LF", "\n");
	define("SYM_PREVIOUS", '<');
	define("SYM_NEXT", '>');
	define("DEFAULT_COLOR", 'PaleVioletRed');
	//Date related constants
	define('PERCENT', 100);
	define('DAYS_IN_WEEK', 7);
	define('SECONDS_PER_DAY', 86400);
	define('HOUR', 3600);
	define('MINUTES', 60);
	//Date formats for use in php date function
	define('YMD', 'Y-m-d');
	define('YMDDY', 'Y-m-d D');
	define('YMDDYHI', 'Y-m-d D H:i');
	define('Y', 'Y');
	define('MONTH', 'Y-m-01');
	define('YEAR', 'Y-01-01');
	define('YEAR_LAST', 'Y-12-31');


	//Defaults
	define('DEFAULT_ACTION', 'logbook');
	define('DEFAULT_ALERT', 'alert-danger');  //class for displaying a bootstrap alert box
	define('DEFAULT_BIRTHDAY', '1960-01-01');
	define('DEFAULT_CONTROLLER', 'activities');
	define('DEFAULT_DATE_FORMAT', YMD);
	define('DEFAULT_DECIMALS', 2);
	define('DEFAULT_IMAGE_DIR', '/img/');
	define('DEFAULT_LAYOUT', 'default');
	define('DEFAULT_METRIC', 'distance');
	define('DEFAULT_TEMPLATE', 'bootstrap');
	define('DEFAULT_TMZ','US/Pacific');
	define('DEFAULT_USER', 2);
	
	define('END_OF_TIME', '2100-01-01');

	define('ELEMENT_EXT', '.php');
	define('VIEW_EXT', '.php');



	//SQL Related constant definitions
	define('DATE_DAY_SQL', '%Y-%m-%d');
	define('DATE_MONTH_FIRST_SQL', '%Y-%m-01');
	define('DATE_YEAR_ONLY_SQL', '%Y-01-01');
	define('ACTIVITY_METRIC_FIELDS',
	' activities.activitytype_id,sum(activities.total_elevation_gain) as total_elevation_gain,  sum(activities.moving_time) as moving_time, sum(activities.distance) as distance, count(*) as count,sum(activities.calories) as calories, count(activities.start_date_local) as count, sum(activities.kudos_count) as kudos_count,sum(achievement_count) as achievement_count, sum(commute) as commute');

	//php date formats

	//Activities

	define('RUNNING', 'Run');
	define('CYCLING', 'Ride');
	define('SWIMMING', 'Swim');
	define('WALKING', 'Walk');
	define('HIKING', 'Hike');
	define('SKIING', "Ski");
	define('OPEN_SWIM', "Open Swim");
	define('KAYAKING', "Kayak");
	define('DEFAULT_ACTIVITY', CYCLING);

	define('METERS_PER_KILOMETER', 1000);
	define('METERS_PER_YARD', 0.9144);
	define('METERS_PER_MILE', 1609.34);
	define('FEET_PER_METER', 3.28084);
	define('UNITS_METRIC', 1);
	define('UNITS_IMPERIAL', 2);
	define('UNITS_STATUTE', 2);
	define('METRIC_LABEL', 'metric');
	define('IMPERIAL_LABEL', 'statute');
	define('STATUTE_LABEL', 'statute');
	define('DEFAULT_UNITS', UNITS_STATUTE);

	define('LOCATION_IMAGES', '/img/');
	define('LOCATION_TYPEIMAGES', 'types/');
	define('LOCATION_ICONS', 'icons/');
	define('LOCATION_LOGOS', 'logos/');
	define('DEFAULT_AVATAR', LOCATION_LOGOS.'defaultStravaAvatar.png');
	define('ICON_ACHIEVEMENTS','icon-trophy.png');
	define('ICON_DOWNLOAD','icon-download.png');
	define('ICON_KUDOS','icon-kudos.png');
	define('ICON_CALORIES', 'icon-calories.png');
	define('ICON_COMMUTE', 'icon-commute.png');
	define('ICON_ALTITUDE', 'icon-mountain.png');
	define('ICON_NEXT', 'icon-next.png');
	define('ICON_PREVIOUS', 'icon-previous.png');
	define('ICON_LAST', 'icon-last.png');
	define('ICON_FIRST', 'icon-first.png');
	define('ICON_SPEED', 'icon-speed.png');
	define('ICON_EDIT', 'icon-edit.png');
	define('ICON_DELETE', 'icon-delete.png');
	define('ICON_DAYS', 'icon-sun.png');
	define('ICON_ELAPSED', 'icon-stopwatch.png');
	define('ICON_REFRESH', 'icon-refresh.png');
	define('ICON_DISTANCE', 'icon-distance.png');
	define('ICON_SEARCH', 'icon-globe.png');

	define('DEFAULT_DOMAIN', 'localhost');
	define('DEFAULT_STATUSMESSAGE', "&nbsp;");
	define('DEFAULT_CYCLING_BUDGET', 10000);
	define('DEFAULT_GRAPH_METRIC', 'distance');



	define('POST', "POST");
	define('GET', "GET");
	define('OK', "OK");


	define('DEFAULT_CITY', 'Seattle');
	define('DEFAULT_LOCALITY', DEFAULT_CITY);
	define('DEFAULT_STATE', 'WA');
	define('DEFAULT_LATITUDE', 47.669280);
	define('DEFAULT_LONGITUDE', -122.274370);

global $DEFAULT_LOCATION;
$DEFAULT_LOCATION = array(	
	'lat'=>'47.6691',
	'lng'=>'-122.274',
	'addr_id'=>'ChIJYfOXt3sTkFQR1kR2ujrWKZ8',
	'addr'=>'4829 Pullman Ave NE, Seattle, WA 98105, USA',
	'neighborhood'=>'Hawthorne Hills',
	'country_long'=>'United States',
	'country_short'=>'US',
	'aal3_long'=>'',
	'aal3_short'=>'',
	'aal2_long'=>'King County',
	'aal2_short'=>'King County',
	'aal1_long'=>'Washington',
	'aal1_short'=>'WA',
	'postal_code'=>'98105',
	'locality'=>'Seattle' 
); 



//Feature Toggles
define("SHOW_KUDOS", true);
define("SHOW_LOGIN", false);
define("SHOW_PHOTOS", true);
define("SHOW_RAW_ACTIVITY_RECORD", false);
define("SHOW_SUN", false);
define("SHOW_PROGRESS", false);
define("SHOW_BUDGET", false);
define("SHOW_SQL", false);
define('SAVE_EFFORTS', false);

	define("ENABLE_GOOGLEMAPS", true);

define('IMG_CYCLING', 'cycling.png');
define('IMG_HIKING', 'hiking.png');
define('IMG_KAYAKING', 'kayaking.png');
define('IMG_OPENSWIMMING', 'openswimming.png');
define('IMG_RUNNING', 'running.png');
define('IMG_SKIING', 'skiing.png');
define('IMG_SWIMMING', 'swimming.png');
define('IMG_WALKING', 'walking.png');




	define('STATPOSITION_CYCLING', 0);
	define('STATPOSITION_SWIMMING', 1);
	define('STATPOSITION_WALKING', 2);
	define('STATPOSITION_SKIING', 3);

	define('CALC_SPEED', 3);
	define('CALC_SECONDS_PER_100_DISTANCE', 1);
	define('CALC_SECONDS_PER_DISTANCE',2);
?>
