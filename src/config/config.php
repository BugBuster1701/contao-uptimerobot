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
        'icon'       => 'system/modules/uptimerobot/assets/monitor_uptimerobot.png',
        'stylesheet' => 'system/modules/uptimerobot/assets/mod_uptimerobot_be.css',
        'javascript' => 'system/modules/uptimerobot/assets/RadialProgressBar.js'
);

