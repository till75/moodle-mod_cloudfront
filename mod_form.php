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
 * The main cloudfront configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage cloudfront
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_cloudfront_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
		global $DB;
        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('cloudfrontname', 'cloudfront'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'cloudfrontname', 'cloudfront');

        // Adding the standard "intro" and "introformat" fields
        $this->add_intro_editor();

        //-------------------------------------------------------------------------------
        // Adding the rest of cloudfront settings, spreading all them into this fieldset
        // or adding more fieldsets ('header' elements) if needed for better logic

		$mform->addElement('header', 'cloudfrontfieldset', 'AWS CloudFront private streaming distribution');  //get_string('cloudfrontfieldset', 'cloudfront'));
        $mform->addElement('text', 'psd_subdomain', 'PSD subdomain', array('size'=>'25'));
        $mform->setType('psd_subdomain', PARAM_TEXT);
        $mform->addElement('text', 'keypair_id', 'Keypair ID', array('size'=>'30'));
        $mform->setType('keypair_id', PARAM_TEXT);
        $mform->addElement('text', 'pkey_filename', 'PKey filename (full path!)', array('size'=>'100'));
        $mform->setType('pkey_filename', PARAM_TEXT);

		$mform->addElement('header', 'cloudfrontfieldset', 'Video configuration');// get_string('cloudfrontfieldset', 'cloudfront'));
        $mform->addElement('text', 'video_filename', 'Video filename (without extension!)', array('size'=>'50'));
        $mform->setType('video_filename', PARAM_TEXT);
		$mform->addElement('text', 'player_width', 'Player width', array('size'=>'4'));
        $mform->setType('player_width', PARAM_INT);
		$mform->addElement('text', 'player_height', 'Player height', array('size'=>'4'));
        $mform->setType('player_height', PARAM_INT);
		$mform->addElement('text', 'expires', 'Expiry (seconds)', array('size'=>'10'));
        $mform->setType('expires', PARAM_INT);
		

        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
