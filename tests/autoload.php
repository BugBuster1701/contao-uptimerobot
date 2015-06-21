<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'bugbuster\\uptimerobot\\dcauptimerobot' => '/../src/classes/DcaUptimeRobot.php',
                'bugbuster\\uptimerobot\\uptimerobotwrapper' => '/../src/classes/UptimeRobotWrapper.php',
                'uptimerobot' => '/../src/classes/UptimeRobot.php',
                'uptimerobot\\uptimerobotwrapper' => '/../src/config/ide_compat.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
// @codeCoverageIgnoreEnd
