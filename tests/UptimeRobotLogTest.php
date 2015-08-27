<?php

/**
 * UptimeRobotLog test case.
 */
class UptimeRobotLogTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var UptimeRobotLog
     */
    private $UptimeRobotLog;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated UptimeRobotLogTest::setUp()
        
        
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated UptimeRobotLogTest::tearDown()
                
        parent::tearDown();
    }


    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotLog::writeLog
     */
    public function testWriteLogOff()
    {
        $return = BugBuster\UptimeRobot\UptimeRobotLog::writeLog(false, false, '');
        $this->assertEquals('Log: No',$return);
        
        $GLOBALS['UptimeRobot']['debug']['status'] = false;
        $return = BugBuster\UptimeRobot\UptimeRobotLog::writeLog(false, false, '');
        $this->assertEquals('Log: No',$return);
    }
    
    /**
     * @covers BugBuster\UptimeRobot\UptimeRobotLog::writeLog
     */
    public function testWriteLogStart()
    {
        $GLOBALS['UptimeRobot']['debug']['status'] = true;
        $return = BugBuster\UptimeRobot\UptimeRobotLog::writeLog('## START ##', '## DEBUG ##', 'ID: 42');
        $this->assertEquals('Start: First',$return);
        
        $return = BugBuster\UptimeRobot\UptimeRobotLog::writeLog('## START ##', '## DEBUG ##', 'ID: 42');
        $this->assertEquals('Start: Not First',$return);
        
    }
}

