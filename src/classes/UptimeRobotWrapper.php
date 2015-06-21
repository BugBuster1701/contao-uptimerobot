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
 * Helper Class UptimeRobotWrapper
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    UptimeRobot
 *
 */
class UptimeRobotWrapper
{
    
    protected $arrMonitorData = array();
    protected $arrApiKeys     = array();

    /**
     * MonitorData Row 
     */
    public function __construct($arrRow = null)
    {
        if (null === $arrRow) 
        {
            return false;
        }
        $this->arrMonitorData = $arrRow;
        $this->arrApiKeys     = array();
        
        return true;
    }

    /**
     * Parse MonitorData Array, set API Keys
     */
    public function parseMonitorData()
    {
        foreach ($this->arrMonitorData as $apikey) 
        {
            if (isset($apikey['monitor_api']))
            {
                $this->arrApiKeys[] = $apikey['monitor_api'];
            }
        }
        if (count($this->arrApiKeys)) 
        {
        	return true;
        }
        return false;
    }
    
    /**
     * Get all monitors
     * 
     * @return  array of stdClass Object[s]
     */
    public function getAllMonitors($apikey = false)
    {
        $upRobot = new \UptimeRobot();
        $upRobot->setFormat('json');
        if (false !== $apikey) 
        {
            $this->arrApiKeys = array();
        	$this->arrApiKeys[] = $apikey;
        }
        $all = array();
        foreach ($this->arrApiKeys as $ApiKey) 
        {
            $upRobot::configure($ApiKey, 1);            

            
            /*
             * Get all monitors
             */
            try
            {
                $all[] = $upRobot->getMonitors();
                //print_r($all);
            }
            catch (\Exception $e) {}
            
            //throw new \Exception(".....");
        }        
        return $all;
    }
    
    public function generateStatus($allMonitors)
    {
        return print_r($allMonitors);
    }
}
    