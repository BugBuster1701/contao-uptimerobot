<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2015 Leo Feyer
 *
 * Contao Module "UptimeRobot"
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    UptimeRobot
 * @license    LGPL
 * @filesource
 * @see	       https://github.com/BugBuster1701/contao-uptimerobot
 */

$GLOBALS['BE_MOD']['system']['uptimerobot'] = array
(
        'tables'     => array('tl_uptimerobot'),
        'icon'       => 'bundles/bugbusteruptimerobot/monitor_uptimerobot.png',
        'stylesheet' => 'bundles/bugbusteruptimerobot/mod_uptimerobot_be.css',
        'javascript' => 'bundles/bugbusteruptimerobot/RadialProgressBar.js'
);

