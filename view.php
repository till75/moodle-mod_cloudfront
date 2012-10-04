<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of cloudfront
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage cloudfront
 * @copyright  2012 Till Seyfarth
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // cloudfront instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('cloudfront', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $cloudfront	= $DB->get_record('cloudfront', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $cloudfront  = $DB->get_record('cloudfront', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cloudfront->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('cloudfront', $cloudfront->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'cloudfront', 'view', "view.php?id={$cm->id}", $cloudfront->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/cloudfront/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($cloudfront->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('cloudfront-'.$somevar);

$PAGE->set_pagelayout('standard');
$PAGE->requires->js('/mod/cloudfront/swfobject.js', true); 

// Output starts here
echo $OUTPUT->header();

if ($cloudfront->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('cloudfront', $cloudfront, $cm->id), 'generalbox mod_introbox', 'cloudfrontintro');
}

$cloudfront_psd_name = $cloudfront->psd_subdomain;
$private_key_filename = $cloudfront->pkey_filename;
$key_pair_id = $cloudfront->keypair_id;
$video_path = $cloudfront->video_filename; // leave off the extention
$expires = time() + $cloudfront->expires;  // in seconds from now
$client_ip = $_SERVER['REMOTE_ADDR'];
$policy = 
'{'.
    '"Statement":['.
        '{'.
            '"Resource":"'. $video_path . '",'.
            '"Condition":{'.
               //'"IpAddress":{"AWS:SourceIp":"' . $client_ip . '/0"},'.
		  '"IpAddress":{"AWS:SourceIp":"' . $client_ip . '/32"},'.
                '"DateLessThan":{"AWS:EpochTime":' . $expires . '}'.
            '}'.
        '}'.
    ']' .
'}';
$custom_policy_stream_name = get_custom_policy_stream_name($video_path, $private_key_filename, $key_pair_id, $policy);

// The following echo orgy worked but I guess it's really really ugly programming. Open to tips on how to improve...

echo "\n<script type='text/javascript'>\n";
echo "  var so_custom = new SWFObject('http://[YOUR-MOODLE-SERVER]/jwplayer/player.swf','mpl', ".$cloudfront->player_width.", ".$cloudfront->player_height.",'9');\n";
echo "  so_custom.addParam('allowfullscreen','true');\n";
echo "  so_custom.addParam('allowscriptaccess','always');\n";
echo "  so_custom.addParam('wmode','opaque');\n";
echo "  so_custom.addVariable('file', 'mp4:".$custom_policy_stream_name."');\n"; 
echo "  so_custom.addVariable('streamer','rtmp://".$cloudfront_psd_name.".cloudfront.net/cfx/st');\n";
echo "  so_custom.write('region-main');\n";
echo "</script>\n";

// Finish the page
echo $OUTPUT->footer();
