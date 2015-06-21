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
    
    private $arrObjMonitors;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $arrRow   = array();
        //read-only key for one monitor over boostrap.php
        $arrRow[] = array('monitor_api' => $GLOBALS['monitor_api']);
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
    public function testGetAllMonitors()
    {
        fwrite(STDOUT,"\n". __METHOD__ . " API Key: ".$GLOBALS['monitor_api']."\n");
        $return = $this->UptimeRobotWrapper->getAllMonitors($GLOBALS['monitor_api']);
        $this->assertNotEquals(null,$return);
        
        $return = $this->UptimeRobotWrapper->getAllMonitors();
        $this->assertNotEquals(null,$return);
        
        $return = $this->UptimeRobotWrapper->getAllMonitors('wrong-key');
        $this->assertEquals('fail',$return[0]->stat);
        
        $this->arrObjMonitors = $return; //zwischenspeichern für nächsten Test
        //print_r($return[0]->monitors->monitor);
    }

    /**
     * Tests UptimeRobotWrapper->generateStatus()
     */
    public function testGenerateStatus()
    {
        // TODO Auto-generated UptimeRobotWrapperTest->testGenerateStatus()
        $this->markTestIncomplete("generateStatus test not implemented");
        
        $this->UptimeRobotWrapper->generateStatus(/* parameters */);
    }
}

