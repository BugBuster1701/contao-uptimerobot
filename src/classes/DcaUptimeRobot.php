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
 * Run in a custom namespace, so the class can be replaced
 */
namespace BugBuster\UptimeRobot;

/**
 * DCA Helper Class DcaUptimeRobot
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    UptimeRobot
 *
 */
class DcaUptimeRobot extends \Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Return the "toggle visibility" button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(\Input::get('tid')))
        {
            $this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }
    
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_uptimerobot::published', 'alexf'))
        {
            return '';
        }
    
        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);
    
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
    
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }
    
    
    /**
     * Disable/enable a check
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        \Input::setGet('id', $intId);
        \Input::setGet('act', 'toggle');
    
    
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_uptimerobot::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish UptimeRobot check "'.$intId.'"', 'tl_uptimerobot toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }
    
        // Update the database
        \Database::getInstance()->prepare("UPDATE
                                                tl_uptimerobot
                                            SET
                                                tstamp=?
                                                , published='" . ($blnVisible ? 1 : '') . "'
                                            WHERE
                                                id=?")
                                                    ->execute(time(),$intId);
        // There can be only one.
        \Database::getInstance()->prepare("UPDATE
                                                tl_uptimerobot
                                            SET
                                                tstamp=?
                                                , published=''
                                            WHERE
                                                id!=?")
                                                    ->execute(time(),$intId);
    
    }
    
    public function showMonitors($arrRow)
    {
        $monitor_data = deserialize($arrRow[monitor_data], true);
        
        $title  ='showMonitors<br>';
        $title .= '<pre>'.print_r($monitor_data, true).'</pre>';
        
        return $title;
    }
    
}
    