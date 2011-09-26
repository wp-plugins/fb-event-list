<?php
/* Plugin Name: Facebook Event List Shortcode
 * Plugin URI: http://www.wordsmith-communication.co.uk/
 * Description: A simple shortcode to generate an event list from a Facebook Fan Page. Requires 
 * Author: Jon Smith
 * Version: 0.1
 * Author URI: http://www.wordsmith-communication.co.uk/
 *
 * Copywrite 2011 Jon Smith (jon@wordsmith-communication.co.uk)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */
 
// make sure this api file is in your directory, if not get it here https://github.com/facebook/php-sdk/tree/master/src
require 'facebook.php';

// [fb_event_list appid="" pageid="" appsecret=""]
function fb_event_list($atts){
	extract(shortcode_atts(array(
	'appid' => '',
	'pageid' => '',
	'appsecret' => '',
	), $atts));

// Authenticate
$facebook = new Facebook(array(
	'appId' => $appid,
	'secret' => $appsecret,
	'cookie' => true, // enable optional cookie support
));

//query the events
//we will select name, pic, start_time, end_time, location, description this time
//but there are other data that you can get on the event table
//as you've noticed, we have TWO select statement here
//since we can't just do "WHERE creator = your_fan_page_id".
//only eid is indexable in the event table, sow we have to retrieve
//list of events by eids
//and this was achieved by selecting all eid from
//event_member table where the uid is the id of your fanpage.
//*yes, you fanpage automatically becomes an event_member
//once it creates an event
ob_start();

$fql    =   "SELECT name, pic, start_time, end_time, location, description, eid 
            FROM event WHERE eid IN ( SELECT eid FROM event_member WHERE uid = " . $pageid . " ) 
            ORDER BY start_time asc";
            
$param  =   array(
'method'    => 'fql.query',
'query'     => $fql,
'callback'  => ''
);

try {
$fqlResult   =   $facebook->api($param);

//looping through retrieved data
foreach( $fqlResult as $keys => $values ){
    //see here for the date format I used
    //The pattern string I used 'l, F d, Y g:i a'
    //will output something like this: July 30, 2015 6:30 pm

    //getting 'start' and 'end' date,
    //'l, F d, Y' pattern string will give us
    //something like: Thursday, July 30, 2015
    $start_date = date( 'l, F d, Y', $values['start_time'] );
    $end_date = date( 'l, F d, Y', $values['end_time'] );

    //getting 'start' and 'end' time
    //'g:i a' will give us something
    //like 6:30 pm
    $start_time = date( 'g:i a', $values['start_time'] );
    $end_time = date( 'g:i a', $values['end_time'] );

    //printing the data
    echo "<div class='event'>";
    echo "<div style='float: left; margin: 0 8px 0 0;'>";
    echo "<a href='http://www.facebook.com/event.php?eid={$values['eid']}'>";
    echo "<img src={$values['pic']} />";
    echo "</a>";
    echo "</div>";
    echo "<div style='float: left;'>";
    echo "<a href='http://www.facebook.com/event.php?eid={$values['eid']}'>";
    echo "<div style='font-size: 26px'>{$values['name']}</div>";
    echo "</a>";
    if( $start_date == $end_date ){
        //if $start_date and $end_date is the same
        //it means the event will happen on the same day
        //so we will have a format something like:
        //July 30, 2015 - 6:30 pm to 9:30 pm
        echo "<div>on {$start_date} - {$start_time} to {$end_time}</div>";
    }else{
        //else if $start_date and $end_date is NOT the equal
        //it means that the event will will be
        //extended to another day
        //so we will have a format something like:
        //July 30, 2013 9:00 pm to Wednesday, July 31, 2013 at 1:00 am
        echo "<div>on {$start_date} {$start_time} to {$end_date} at {$end_time}</div>";
    }
    echo "<div>Location: " . $values['location'] . "</div>";
    echo "<div>More Info: " . $values['description'] . "</div>";
    echo "</div>";
    echo "<div style='clear: both'></div>";
    echo "</div>";
    }
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

    $htmlOutput = ob_get_clean();	
    return $htmlOutput;
}

add_shortcode('fb_event_list', 'fb_event_list');
// end fb_event_list shortcode
 
?>