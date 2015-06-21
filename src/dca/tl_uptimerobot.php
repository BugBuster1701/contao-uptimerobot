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

/**
 * Table tl_uptimerobot
 */
$GLOBALS['TL_DCA']['tl_uptimerobot'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id'    => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('monitor_group'),
        ),
        'label' => array
        (
            'fields'                  => array('monitor_group'),
            'format'                  => '%s' ,
            'label_callback'          => array('BugBuster\UptimeRobot\DcaUptimeRobot', 'showMonitors')
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_uptimerobot']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_uptimerobot']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_uptimerobot']['toggle'],
                'icon'                => 'visible.gif',
                //'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('BugBuster\UptimeRobot\DcaUptimeRobot', 'toggleIcon')
            )
        )
        
    ),
    
    // Palettes
    'palettes' => array
    (
        //'__selector__'                => array(),
        'default'                     => 'monitor_group,monitor_data;published,monitor_debug'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'          => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'          => "int(10) unsigned NOT NULL default '0'"
        ),
    	'monitor_group' => array
    	(
	        'label'        => &$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_group'],
	        'exclude'      => true,
	        'inputType'    => 'text',
	        'eval'         => array('mandatory'=>true, 'maxlength'=>255),
	        'sql'          => "varchar(255) NOT NULL default ''"
    	),
        'monitor_data' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_data'],
            'exclude'                 => true,
            'inputType'               => 'multiColumnWizard',
            'sql'                     => "blob NULL",
            'eval'                    => array
            (
                'columnFields' => array
                (
                    'monitor_api' => array
                    (
                        'label'       => &$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_api'],
                        'exclude'     => true,
                        'inputType'   => 'text',
                        'eval'        => array('mandatory'=>true)
                    ),
                )//columnFields
            )//eval of monitor_data
        ),//monitor_data
        'published' => array
        (
            'exclude'             => true,
            'label'               => &$GLOBALS['TL_LANG']['tl_uptimerobot']['published'],
            'inputType'           => 'checkbox',
            'eval'                => array('doNotCopy'=>true, 'tl_class' => 'w50'),
            'sql'                 => "char(1) NOT NULL default ''"
        ),
        'monitor_debug' => array
        (
            'label'               => &$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_debug'],
            'exclude'             => true,
            'inputType'           => 'checkbox',
            'eval'                => array('tl_class' => 'w50'),
            'sql'                 => "char(1) NOT NULL default ''"
        )
    )//fields
);
