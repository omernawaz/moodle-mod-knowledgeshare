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
 * @package   local_message
 * @copyright 2024, Omer Nawaz <omarnawaz29@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace mod_knowledgeshare\form;

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

require_once($CFG->dirroot . '/mod/forum/lib.php');
require_once($CFG->libdir . '/formslib.php');

class create_post extends \moodleform
{
    public function definition()
    {

        global $CFG, $COURSE, $PAGE;
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'name', get_string('create:title', 'mod_knowledgeshare'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', 'client');

        $context = \context_course::instance($COURSE->id);
        $mform->addElement('editor', 'intro_editor', get_string('create:desc', 'mod_knowledgeshare'), null, array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $context));
        $mform->setType('introeditor', PARAM_RAW);

        //$this->standard_coursemodule_elements();

        $this->add_action_buttons(true, get_string('create:btn-create', 'mod_knowledgeshare'));
    }

    // Custom validation should be added here.
    function validation($data, $files)
    {
        $errors = array();
        if (empty($data['name'])) {
            $errors['name'] = get_string('form:errornotitle', 'mod_knowledgeshare');
        }
        if (empty($data['introeditor']['text'])) {
            $errors['introeditor'] = get_string('form:errornointro', 'mod_knowledgeshare');
        }
    }
}
