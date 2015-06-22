<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'BugBuster',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'BugBuster\UptimeRobot\DcaUptimeRobot' => 'system/modules/uptimerobot/classes/DcaUptimeRobot.php',
	'UptimeRobot'                          => 'system/modules/uptimerobot/classes/UptimeRobot.php',
));