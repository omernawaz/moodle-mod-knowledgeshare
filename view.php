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
 * Activity view page for the plugintype_pluginname plugin.
 *
 * @package   mod_knowledgeshare
 * @copyright 2024, Omer Nawaz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');



$id = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($id, 'knowledgeshare');
$instance = $DB->get_record('knowledgeshare', ['id' => $cm->instance], '*', MUST_EXIST);

require_course_login($course, false, $cm);

$PAGE->set_url(new moodle_url('/mod/knowledgeshare;/view.php'));
$PAGE->set_context(\context_module::instance($cm->id));

$posts = [];

$templatecontext = array(
    'title' => $instance->name,
    'desc' => $instance->intro,
    'posts' => $posts,
    'time' => $instance->timemodified,
);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('mod_knowledgeshare/view', $templatecontext);

echo $OUTPUT->footer();
