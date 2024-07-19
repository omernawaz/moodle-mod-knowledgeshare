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

use mod_knowledgeshare\form\create_post;

require_once(__DIR__ . '/../../config.php');


$id = optional_param('id', -1, PARAM_INT);

if ($id == -1) {
    $id = $_SESSION['cmid'];
} else {
    $_SESSION['cmid'] = $_GET['id'];
}

[$course, $cm] = get_course_and_cm_from_cmid($id, 'knowledgeshare');
$instance = $DB->get_record('knowledgeshare', ['id' => $cm->instance], '*', MUST_EXIST);
$context = \context_module::instance($cm->id);
require_course_login($course, false, $cm);
$PAGE->set_context($context);


$PAGE->set_url(new moodle_url('/mod/knowledgeshare;/create_post.php'));
$PAGE->set_title(get_string('create:pagetitle', 'mod_knowledgeshare'));
$PAGE->set_heading(get_string('create:heading', 'mod_knowledgeshare'));

//require_capability('mod/knowledgeshare:createpost', $context);

$mform = new create_post();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . "/mod/knowledgeshare/view.php?id=$id", get_string('create:cancelled', 'mod_knowledgeshare'));
} else if ($fromform = $mform->get_data()) {

    $record = new stdClass();
    global $USER;

    $fieldoptions = [
        'trusttext' => true,
        'maxfiles' => 99,
        'maxbytes' => $course->maxbytes,
        'context' => $context,
        'forcehttps' => false
    ];
    $itemid = $fromform->intro_editor['itemid'];

    $data = file_postupdate_standard_editor(
        $fromform,
        'intro',
        $fieldoptions,
        $context,
        'mod_knowledgeshare',
        'student_data',
        $itemid
    );


    $record->mod_id = $cm->id;
    $record->author_id = $USER->id;
    $record->title = $fromform->name;
    $record->content = $data->intro;
    $record->timemodified = time();
    $record->itemid = $itemid;



    $DB->insert_record('knowledgeshare_posts', $record);

    redirect($CFG->wwwroot . "/mod/knowledgeshare/view.php?id=$id", null);
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
