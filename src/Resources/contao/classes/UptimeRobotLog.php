<?php
/**
 * Extension for Contao Open Source CMS, Copyright (C) 2005-2015 Leo Feyer
 *
 * Class UptimeRobotLog
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @licence    LGPL
 * @filesource
 * @package    UptimeRobot
 * @see	       https://github.com/BugBuster1701/contao-uptimerobot
 */

namespace BugBuster\UptimeRobot;

/**
 * Class UptimeRobotLog
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    UptimeRobot
 * @license    LGPL
 */
class UptimeRobotLog
{
    /**
     * Write in log file, if debug is enabled
     *
     * @param string    $method
     * @param integer   $line
     * @param string    $value
     * @param boolean   $debug  true=yes, false=no
     */
    public static function writeLog($method, $line, $value)
    {
        if (!isset($GLOBALS['UptimeRobot']['debug']['status']) ||  
            false === (bool) $GLOBALS['UptimeRobot']['debug']['status'] 
           ) 
        {
        	return 'Log: No';
        }
        
        if ($method == '## START ##') 
        {
            if (!isset($GLOBALS['UptimeRobot']['debug']['first'])) 
            {
                $arrUniqid = trimsplit('.', uniqid('c0n7a0',true) );
                $GLOBALS['UptimeRobot']['debug']['first'] = $arrUniqid[1];
                log_message(sprintf('[%s] [%s] [%s] %s',
                                    $GLOBALS['UptimeRobot']['debug']['first'],
                                    $method,
                                    $line,
                                    $value), 'uptime-robot_debug.log');
                return 'Start: First';
            }
            else
            {
                return 'Start: Not First';
            }
        }
                
        $arrNamespace = trimsplit('::', $method);
        $arrClass =  trimsplit('\\', $arrNamespace[0]);
        $vclass = $arrClass[2]; // class that will write the log
        
        if (is_array($value))
        {
            $value = print_r($value,true);
        }
        
        $strMessage = sprintf('[%s] [%s] [%s] %s',$GLOBALS['UptimeRobot']['debug']['first'],$vclass.'::'.$arrNamespace[1],$line,$value);
        
        if (($container = \System::getContainer()) !== null)
        {
            $strLogsDir = $container->getParameter('kernel.logs_dir');
        }
        
        if (!$strLogsDir)
        {
            $strLogsDir = TL_ROOT . '/var/logs';
        }
        
        $strLog = 'prod-' . date('Y-m-d') . '-' . 'uptime-robot' . '.log';
        
        error_log(sprintf("[%s] %s\n", date('d-M-Y H:i:s'), $strMessage), 3, $strLogsDir . '/' . $strLog);

        return 'Log: Yes';
    }
}
