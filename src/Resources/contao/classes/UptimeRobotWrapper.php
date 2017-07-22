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

namespace BugBuster\UptimeRobot;

//use BugBuster\UptimeRobot\UptimeRobotLog;


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
        if (!$this->arrMonitorData)
            return false;
        
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
        $mon = array();
        foreach ($this->arrApiKeys as $ApiKey) 
        {
            $upRobot::configure($ApiKey, 1);            

            
            /*
             * Get all monitors
             */
            try
            {
                // logs=1, all_time_uptime_ratio=1
                $mon = $upRobot->getMonitors('','',1,'','','','','',1);
                //UptimeRobotLog::writeLog(__METHOD__ , __LINE__ , 'Monitors: '. print_r($mon,true));
                $all[] = $mon;                
            } 
            catch (\Throwable $t) 
            {
                // Executed only in PHP 7, will not match in PHP 5.x
            }
            catch (\Exception $e) 
            {
                // Executed only in PHP 5.x, will not be reached in PHP 7
            }
            
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
        	    $allMonitorPagination = $allMonitor->pagination;
        	    
            	$objMonitors = $allMonitor->monitors;
            	foreach ($objMonitors as $objMonitor) 
            	{
                	$id = $objMonitor->id;
                	$friendlyname = $objMonitor->friendly_name;
                	
                	$genStatus[] = array('stat'           => $allMonitor->stat // ok
                	                   , 'id'             => $objMonitor->id
                	                   , 'friendlyname'   => $objMonitor->friendly_name
                                       , 'url'            => $objMonitor->url
                	                   , 'monitor_type'   => $this->translateMonitorType($objMonitor->type)
                	                   //, 'monitor_status' => $this->translateMonitorStatus($objMonitor->status)
                	                   , 'monitor_status_id'  => $objMonitor->status
                	                   , 'alltimeuptimeratio' => $objMonitor->all_time_uptime_ratio
                	                   , 'limit'              => $allMonitorPagination->limit
                                       , 'total'              => $allMonitorPagination->total
                	                  );
            	}
        	}
        	else 
        	{
        	    $allMonitorError = $allMonitor->error;
        	    $genStatus[] = array('stat'    => $allMonitor->stat // fail
        	                       , 'message' => $allMonitorError->message
                                   , 'monitor_status_id'  => 4
                                   , 'limit'              => 0
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
    