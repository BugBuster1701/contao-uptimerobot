<?php

if (file_exists(dirname(__FILE__) . '/apikey.php')) 
{
    //Set the apiKey for tests, the read-only key is enough
    require_once dirname(__FILE__) . '/apikey.php';
}
else 
{
    echo "File: ".dirname(__FILE__) . '/apikey.php'." not found\n";
    echo "Create a file ".dirname(__FILE__) . "/apikey.php with this content:\n";
    echo "<?php \n\$GLOBALS['monitor_api'][0] = 'your-fist-monitor-api-key';\n";
    echo "\$GLOBALS['monitor_api'][1] = 'your-second-monitor-api-key';\n";
    echo "....\n\n";
    exit(255);
}

//autoload.php generated with phpab
//phpab -o tests/autoload.php  -b tests src
require_once dirname(__FILE__) . '/autoload.php';
