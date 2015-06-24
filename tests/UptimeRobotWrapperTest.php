<?php

/**
 * UptimeRobotWrapper test case.
 */
class UptimeRobotWrapperTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var UptimeRobotWrapper
     */
    private $UptimeRobotWrapper;
    
    private static $arrObjMonitors;

    /**
     * called before the first test of the test case class is run
     * also see: tearDownAfterClass()
     */
    public static function setUpBeforeClass()
    {
        //read-only key(s) for monitors over phpunit.xml[.dist]
        $GLOBALS['monitor_api'] = json_decode($GLOBALS['monitor_api_keys'], true);
        //fwrite(STDOUT,"\n". __METHOD__ . " monitor_api: ".print_r($GLOBALS['monitor_api'],true)."\n");
    }
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $arrRow   = array();
        //read-only key for one monitor over boostrap.php
        foreach ($GLOBALS['monitor_api'] as $value) 
        {
            $arrRow[] = array('monitor_api' => $value);
        }
        //fwrite(STDOUT,"\n". __METHOD__ . " API Key: ".print_r($arrRow,true)."\n");
        $this->UptimeRobotWrapper = new BugBuster\UptimeRobot\UptimeRobotWrapper($arrRow);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated UptimeRobotWrapperTest::tearDown()
        $this->UptimeRobotWrapper = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::__construct
     */
    public function test__construct()
    {
        $return = $this->UptimeRobotWrapper->__construct(/* parameters */);
        $this->assertFalse($return);
        $return = $this->UptimeRobotWrapper->__construct(array());
        $this->assertTrue($return);
        
    }

    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::parseMonitorData
     */
    public function testParseMonitorData()
    {
        $return = $this->UptimeRobotWrapper->parseMonitorData(/* parameters */);
        $this->assertTrue($return);
    }

    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::getAllMonitors
     */
    public function testGetAllMonitorsWrongKey()
    {
        $return = $this->UptimeRobotWrapper->getAllMonitors('wrong-key');
        $this->assertEquals('fail',$return[0]->stat);
    }
    
    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::getAllMonitors
     */
    public function testGetAllMonitorsSingle()
    {
        //fwrite(STDOUT,"\n". __METHOD__ . " API Key: ".$GLOBALS['monitor_api'][0]."\n");
        $return = $this->UptimeRobotWrapper->getAllMonitors($GLOBALS['monitor_api'][0]);
        $this->assertNotEquals(null,$return);
    }
    
    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::getAllMonitors
     */
    public function testGetAllMonitorsMultiple()
    {
        $this->UptimeRobotWrapper->parseMonitorData(/* parameters */);
        $return = $this->UptimeRobotWrapper->getAllMonitors();
        $this->assertNotEquals(null,$return);
        self::$arrObjMonitors = $return; //zwischenspeichern für nächsten Test
        //fwrite(STDOUT,"\n". __METHOD__ . " arrObjMonitors: ".print_r(self::$arrObjMonitors,true)."\n");
    }
    
    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::translateMonitorType
     */
    public function testTranslateMonitorType()
    {
        //fwrite(STDOUT,"\n". __METHOD__ . "\n");
        $return = $this->UptimeRobotWrapper->translateMonitorType(1);
        $this->assertEquals('HTTP(s)',$return);
    }
    
    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::generateStatus
     * @covers BugBuster\UptimeRobot\UptimeRobotWrapper::translateMonitorType
     */
    public function testGenerateStatus()
    {
        //fwrite(STDOUT,"\n". __METHOD__ . " arrObjMonitors: ".print_r(self::$arrObjMonitors,true)."\n");
        $return = $this->UptimeRobotWrapper->generateStatus(self::$arrObjMonitors);
        $this->assertNotEquals(null,$return);
        //fwrite(STDOUT,"\n" . print_r($return,true) ."\n");
        
        self::$arrObjMonitors = null;
        self::$arrObjMonitors[0] = new stdClass();
        self::$arrObjMonitors[0]->stat    = 'fail';
        self::$arrObjMonitors[0]->message = 'wrong key';
        self::$arrObjMonitors[0]->limit   = 50;
        //fwrite(STDOUT,"\n". __METHOD__ . " arrObjMonitors: ".print_r(self::$arrObjMonitors,true)."\n");
        $return = $this->UptimeRobotWrapper->generateStatus(self::$arrObjMonitors);
        $this->assertEquals('fail',$return[0]['stat']);
        //fwrite(STDOUT,"\n" . print_r($return,true) ."\n");
    }
}

