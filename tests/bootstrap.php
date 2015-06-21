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
    echo "<?php \n\$GLOBALS['monitor_api'] = 'your-monitor-api-key';\n\n";
    exit(255);
}

//autoload.php generated with phpab
//phpab -o tests/autoload.php  -b tests src
require_once dirname(__FILE__) . '/autoload.php';
