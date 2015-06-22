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
     * Tests UptimeRobotWrapper->__construct()
     */
    public function test__construct()
    {
        $return = $this->UptimeRobotWrapper->__construct(/* parameters */);
        $this->assertFalse($return);
        $return = $this->UptimeRobotWrapper->__construct(array());
        $this->assertTrue($return);
        
    }

    /**
     * Tests UptimeRobotWrapper->parseMonitorData()
     */
    public function testParseMonitorData()
    {
        $return = $this->UptimeRobotWrapper->parseMonitorData(/* parameters */);
        $this->assertTrue($return);
    }

    /**
     * Tests UptimeRobotWrapper->getAllMonitors()
     */
    public function testGetAllMonitorsWrongKey()
    {
        $return = $this->UptimeRobotWrapper->getAllMonitors('wrong-key');
        $this->assertEquals('fail',$return[0]->stat);
    }
    
    /**
     * Tests UptimeRobotWrapper->getAllMonitors()
     */
    public function testGetAllMonitorsSingle()
    {
        //fwrite(STDOUT,"\n". __METHOD__ . " API Key: ".$GLOBALS['monitor_api'][0]."\n");
        $return = $this->UptimeRobotWrapper->getAllMonitors($GLOBALS['monitor_api'][0]);
        $this->assertNotEquals(null,$return);
    }
    
    /**
     * Tests UptimeRobotWrapper->getAllMonitors()
     */
    public function testGetAllMonitorsMultiple()
    {
        $this->UptimeRobotWrapper->parseMonitorData(/* parameters */);
        $return = $this->UptimeRobotWrapper->getAllMonitors();
        $this->assertNotEquals(null,$return);
        self::$arrObjMonitors = $return; //zwischenspeichern für nächsten Test
        //fwrite(STDOUT,"\n". __METHOD__ . " arrObjMonitors: ".print_r(self::$arrObjMonitors,true)."\n");
    }
    
    public function testTranslateMonitorType()
    {
        //fwrite(STDOUT,"\n". __METHOD__ . "\n");
        $return = $this->UptimeRobotWrapper->translateMonitorType(1);
        $this->assertEquals('HTTP(s)',$return);
    }
    
    /**
     * Tests UptimeRobotWrapper->generateStatus()
     */
    public function testGenerateStatus()
    {
        fwrite(STDOUT,"\n". __METHOD__ . " arrObjMonitors: ".print_r(self::$arrObjMonitors,true)."\n");
        $return = $this->UptimeRobotWrapper->generateStatus(self::$arrObjMonitors);
        $this->assertNotEquals(null,$return);
        fwrite(STDOUT,"\n" . print_r($return,true) ."\n");
    }
}

