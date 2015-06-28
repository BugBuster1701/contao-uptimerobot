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

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace BugBuster\UptimeRobot;

/**
 * DCA Helper Class DcaUptimeRobot
 *
 * @copyright  Glen Langer 2015 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    UptimeRobot
 *
 */
class DcaUptimeRobot extends \Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Return the "toggle visibility" button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(\Input::get('tid')))
        {
            $this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }
    
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_uptimerobot::published', 'alexf'))
        {
            return '';
        }
    
        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);
    
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
    
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }
    
    
    /**
     * Disable/enable a check
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        \Input::setGet('id', $intId);
        \Input::setGet('act', 'toggle');
    
    
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_uptimerobot::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish UptimeRobot check "'.$intId.'"', 'tl_uptimerobot toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }
    
        // Update the database
        \Database::getInstance()->prepare("UPDATE
                                                tl_uptimerobot
                                            SET
                                                tstamp=?
                                                , published='" . ($blnVisible ? 1 : '') . "'
                                            WHERE
                                                id=?")
                                ->execute(time(),$intId);
    }
    
    /**
     * Show Monitors, DCA Callback Aufruf
     * @param unknown $arrRow
     * @return string
     */
    public function showMonitors($arrRow)
    {
        $lineCount = 0;

        $icon_0 = \Image::getHtml('invisible.gif', $GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_0'], 'title="' .specialchars($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_0']).'"');
        $icon_1 = \Image::getHtml('invisible.gif', $GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_1'], 'title="' .specialchars($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_1']).'"');
        $icon_2 = \Image::getHtml('ok.gif'       , $GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_2'], 'title="' .specialchars($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_2']).'"');
        $icon_3 = \Image::getHtml('about.gif'    , $GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_3'], 'title="' .specialchars($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_3']).'"');
        $icon_4 = \Image::getHtml('error.gif'    , $GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_4'], 'title="' .specialchars($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_status_4']).'"');
        
        if (true === (bool) $arrRow['published']) 
        {
            $this->UptimeRobotWrapper = new \UptimeRobot\UptimeRobotWrapper($arrRow);
            $this->UptimeRobotWrapper->parseMonitorData();
            $arrObjMonitors    = $this->UptimeRobotWrapper->getAllMonitors();
            $arrMonitorsStatus = $this->UptimeRobotWrapper->generateStatus($arrObjMonitors);
        }
        else 
        {
            $arrMonitorsStatus = array();
        }
        $table = '
<table class="tl_listing_checks" id="tg-oLiAr">
  <tr>
    <th class="tl_folder_tlist" style="text-align: center;">'.$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_legend_status'].'</th>
    <th class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_legend_name'].'</th>
    <th class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_legend_url'].'</th>        
    <th class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_legend_type'].'</th>
    <th class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_legend_ratio'].'</th>
  </tr>
';
        
        foreach ($arrMonitorsStatus as $arrMonitorStatus) 
        {
        	if ($arrMonitorStatus['stat'] == 'ok') 
        	{
        	    $merge = 'icon_'.$arrMonitorStatus['monitor_status_id'];
        	    $icon  = $$merge;
        	    
        	    $class = (($lineCount % 2) == 0) ? 'even' : 'odd';
        		$table .= '<tr class="'.$class.'">
    <td class="tl_file_list" style="text-align: center;">'.$icon.'</td>
    <td class="tl_file_list">'.$arrMonitorStatus['friendlyname'].'</td>
    <td class="tl_file_list">'.$arrMonitorStatus['url'].'</td>
    <td class="tl_file_list">'.$arrMonitorStatus['monitor_type'].'</td>
    <td class="tl_file_list">'.$arrMonitorStatus['alltimeuptimeratio'].'</td>
  </tr>
';
        		$lineCount++;
        	}
        	else 
        	{
        	    $class = (($lineCount % 2) == 0) ? 'even' : 'odd';
        	    $table .= '<tr class="'.$class.'">
    <td class="tl_file_list" style="text-align: center;">'.$icon_4.'</td>
    <td class="tl_file_list" colspan="4">Error: '.$arrMonitorStatus['message'].'</td>
  </tr>
';
        	    $lineCount++;
        	}
        }//foreach
        $table .= '</table>
<script type="text/javascript" charset="utf-8">var TgTableSort=window.TgTableSort||function(n,t){"use strict";function r(n,t){for(var e=[],o=n.childNodes,i=0;i<o.length;++i){var u=o[i];if("."==t.substring(0,1)){var a=t.substring(1);f(u,a)&&e.push(u)}else u.nodeName.toLowerCase()==t&&e.push(u);var c=r(u,t);e=e.concat(c)}return e}function e(n,t){var e=[],o=r(n,"tr");return o.forEach(function(n){var o=r(n,"td");t>=0&&t<o.length&&e.push(o[t])}),e}function o(n){return n.textContent||n.innerText||""}function i(n){return n.innerHTML||""}function u(n,t){var r=e(n,t);return r.map(o)}function a(n,t){var r=e(n,t);return r.map(i)}function c(n){var t=n.className||"";return t.match(/\S+/g)||[]}function f(n,t){return-1!=c(n).indexOf(t)}function s(n,t){f(n,t)||(n.className+=" "+t)}function d(n,t){if(f(n,t)){var r=c(n),e=r.indexOf(t);r.splice(e,1),n.className=r.join(" ")}}function v(n){d(n,L),d(n,E)}function l(n,t,e){r(n,"."+E).map(v),r(n,"."+L).map(v),e==T?s(t,E):s(t,L)}function g(n){return function(t,r){var e=n*t.str.localeCompare(r.str);return 0==e&&(e=t.index-r.index),e}}function h(n){return function(t,r){var e=+t.str,o=+r.str;return e==o?t.index-r.index:n*(e-o)}}function m(n,t,r){var e=u(n,t),o=e.map(function(n,t){return{str:n,index:t}}),i=e&&-1==e.map(isNaN).indexOf(!0),a=i?h(r):g(r);return o.sort(a),o.map(function(n){return n.index})}function p(n,t,r,o){for(var i=f(o,E)?N:T,u=m(n,r,i),c=0;t>c;++c){var s=e(n,c),d=a(n,c);s.forEach(function(n,t){n.innerHTML=d[u[t]]})}l(n,o,i)}function x(n,t){var r=t.length;t.forEach(function(t,e){t.addEventListener("click",function(){p(n,r,e,t)}),s(t,"tg-sort-header")})}var T=1,N=-1,E="tg-sort-asc",L="tg-sort-desc";return function(t){var e=n.getElementById(t),o=r(e,"tr"),i=o.length>0?r(o[0],"td"):[];0==i.length&&(i=r(o[0],"th"));for(var u=1;u<o.length;++u){var a=r(o[u],"td");if(a.length!=i.length)return}x(e,i)}}(document);document.addEventListener("DOMContentLoaded",function(n){TgTableSort("tg-oLiAr")});</script>
';
        
        if ($arrMonitorStatus['stat'] == 'ok')
        {
            $table .= '<table class="tl_listing_checks">';
            $table .= '
  <tr>
    <td class="tl_file_list">&nbsp;</td>
  </tr>
  <tr>
    <td class="tl_folder_tlist" style="text-align: center;">'.sprintf($GLOBALS['TL_LANG']['tl_uptimerobot']['monitor_limit'], $lineCount, $arrMonitorStatus['limit']).'</td>
  </tr>
';
            $table .= '</table>
';
        }
        
        return $table;
    }
    
}
    