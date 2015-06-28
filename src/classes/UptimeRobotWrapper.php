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
    protected $debug          = false;
    
    const STAT_OK   = 'ok';
    const STAT_FAIL = 'fail';
    
    public static $MONITOR_TYPE = array(1 => 'HTTP(s)'
                                      , 2 => 'Keyword'
                                      , 3 => 'Ping'
                                      , 4 => 'Port'
                                      );
    
    // only for Monitor Type 4
    public static $MONITOR_SUBTYPE = array(1  => 'HTTP (80)'
                                         , 2  => 'HTTPS (443)'
                                         , 3  => 'FTP (21)'
                                         , 4  => 'SMTP (25)'
                                         , 5  => 'POP3 (110)'
                                         , 6  => 'IMAP (143)'
                                         , 99 => 'Custom Port'
                                         );
    
    // only for Monitor Type 2
    public static $MONITOR_KEYWORDTYPE = array(1 => 'exists'
                                             , 2 => 'not exists'
                                             );
    
    //TODO über Sprachfiles
    public static $MONITOR_STATUS = array(0 => 'paused'             // invisible.gif
                                        , 1 => 'not checked yet'    // invisible.gif
                                        , 2 => 'up'                 // ok.gif
                                        , 3 => 'seems down'         // about.gif
                                        , 4 => 'down'               // error.gif
                                        );
    

    /**
     * DCA Row
     */
    public function __construct($arrRow = null)
    {
        if (null === $arrRow) 
        {
            return false;
        }
        //deserialize not work on phphunit, no functions.php at this time
        $monitor_data = @unserialize($arrRow[monitor_data]);
        
        $this->arrMonitorData = $monitor_data;
        $this->arrApiKeys     = array();
        $this->debug          = (bool) $arrRow['monitor_debug'];
        
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
        $genStatus = array();
        
        foreach ($allMonitors as $allMonitor) 
        {
        	if ($allMonitor->stat == self::STAT_OK)
        	{
            	$objMonitors = $allMonitor->monitors;
            	$objMonitor  = $objMonitors->monitor[0];
            	
            	$id = $objMonitor->id;
            	$friendlyname = $objMonitor->friendlyname;
            	
            	$genStatus[] = array('stat'           => $allMonitor->stat // ok
            	                   , 'id'             => $objMonitor->id
            	                   , 'friendlyname'   => $objMonitor->friendlyname
                                   , 'url'            => $objMonitor->url
            	                   , 'monitor_type'   => $this->translateMonitorType($objMonitor->type)
            	                   //, 'monitor_status' => $this->translateMonitorStatus($objMonitor->status)
            	                   , 'monitor_status_id'  => $objMonitor->status
            	                   , 'alltimeuptimeratio' => $objMonitor->alltimeuptimeratio
            	                   , 'limit'              => $allMonitor->limit
            	                  );
        	}
        	else 
        	{
        	    $genStatus[] = array('stat'    => $allMonitor->stat // fail
        	                       , 'message' => $allMonitor->message
                                   , 'monitor_status_id'  => 4
                                   , 'limit'              => $allMonitor->limit
        	                        );
        	}
        }
        return $genStatus;
    }
    
    public function translateMonitorType($monitor_type)
    {
        return self::$MONITOR_TYPE[$monitor_type];
    }
    
    /*
    public function translateMonitorStatus($monitor_status)
    {
        return self::$MONITOR_STATUS[$monitor_status];
    }
    */
    
    
    
}
    