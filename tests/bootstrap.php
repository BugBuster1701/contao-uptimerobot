<?php

//autoload.php generated with phpab
//phpab -o tests/autoload.php  -b tests src
//https://github.com/theseer/Autoload
require_once dirname(__FILE__) . '/autoload.php';

function log_message($strMessage, $strLog='error.log')
{
    @error_log(sprintf("[%s] %s\n", date('d-M-Y H:i:s'), $strMessage), 3, dirname(__FILE__) . '/../build/' . $strLog);
}

/**
 * Split a string into fragments, remove whitespace and return fragments as array
 * @param string
 * @param string
 * @return array
 */
function trimsplit($strPattern, $strString)
{
    global $arrSplitCache;
    $strKey = md5($strPattern.$strString);

    // Load from cache
    if (isset($arrSplitCache[$strKey]))
    {
        return $arrSplitCache[$strKey];
    }

    // Split
    if (strlen($strPattern) == 1)
    {
        $arrFragments = array_map('trim', explode($strPattern, $strString));
    }
    else
    {
        $arrFragments = array_map('trim', preg_split('/'.$strPattern.'/ui', $strString));
    }

    // Empty array
    if (count($arrFragments) < 2 && !strlen($arrFragments[0]))
    {
        $arrFragments = array();
    }

    $arrSplitCache[$strKey] = $arrFragments;
    return $arrFragments;
}
