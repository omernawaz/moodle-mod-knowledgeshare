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

function fetch_user_by_id($id)
{
    global $DB;
    $record = $DB->get_record('user', array('id' => $id), 'firstname,lastname');
    return $record->firstname . ' ' . $record->lastname;
}

function has_already_upvoted_post($post, $userid)
{
    global $DB;
    $already_upvoted = $DB->get_record('knowledgeshare_upvotes', ['post_id' => $post->id, 'upvoter_id' => $userid]);
    if (!empty($already_upvoted)) {
        return true;
    }
    return false;
}

function fetch_replies_for_post($post)
{
    global $DB, $USER;
    $replies = $DB->get_records('knowledgeshare_comments', ['post_id' => $post->id], 'timemodified desc');

    foreach ($replies as $reply) {
        $reply->author = fetch_user_by_id($reply->author_id);
        $reply->comment = $reply->content;

        if ($reply->author_id == $USER->id) {
            $reply->self_reply = true;
        }
    }

    return $replies;
}

function fetch_posts($mod_id, $context)
{
    global $DB, $USER;
    $posts = $DB->get_records('knowledgeshare_posts', array('mod_id' => $mod_id), 'timemodified desc');



    foreach ($posts as $post) {

        if ($post->author_id == $USER->id) {
            $post->self_post = true;
        }

        $post->upvoted = has_already_upvoted_post($post, $USER->id);

        $post->username = fetch_user_by_id($post->author_id);

        $post->replies = array_values(fetch_replies_for_post($post));

        $post->content = file_rewrite_pluginfile_urls($post->content, 'pluginfile.php', $context->id, 'mod_knowledgeshare', 'student_data', $post->itemid);
    }

    return $posts;
}

$id = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($id, 'knowledgeshare');
$instance = $DB->get_record('knowledgeshare', ['id' => $cm->instance], '*', MUST_EXIST);
$context = \CONTEXT_MODULE::instance($cm->id);
require_course_login($course, false, $cm);

$PAGE->set_url(new moodle_url('/mod/knowledgeshare;/view.php'));
$PAGE->set_context($context);
$PAGE->requires->js_call_amd('mod_knowledgeshare/upvote');
$PAGE->requires->js_call_amd('mod_knowledgeshare/reply');
$PAGE->requires->js_call_amd('mod_knowledgeshare/delete');




$posts = fetch_posts($id, $context);

$templatecontext = array(
    'posts' => array_values($posts),
    'time' => $instance->timemodified,
    'createurl' => new moodle_url('/mod/knowledgeshare/create_post.php'),
    'moduleid' => $id,
);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('mod_knowledgeshare/view', $templatecontext);

echo $OUTPUT->footer();
