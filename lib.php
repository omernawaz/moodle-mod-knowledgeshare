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
 * Mandatory public API of folder module
 *
 * @package   mod_knowledgeshare
 * @copyright 2024, Omer Nawaz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function knowledgeshare_supports($feature)
{
    switch ($feature) {
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return false;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_COLLABORATION;

        default:
            return null;
    }
}

function knowledgeshare_add_instance($instancedata, $mform = null): int
{
    global $DB;
    $instancedata->timemodified = time();
    $instance_id = $DB->insert_record('knowledgeshare', $instancedata);


    return $instance_id;
}
function knowledgeshare_update_instance($instancedata, $mform): bool
{
    global $DB;
    $instancedata->timemodified = time();
    $instancedata->id = $instancedata->instance;


    $status = $DB->update_record('knowledgeshare', $instancedata);
    return $status;
}

function knowledgeshare_delete_instance($id): bool
{
    global $DB;

    if (!$mod = $DB->get_record('knowledgeshare', ['id' => $id]))
        return false;
    if (!$cm = get_coursemodule_from_instance('knowledgeshare', $mod->id))
        return false;

    $context = \context_module::instance($cm->id);

    $fs = get_file_storage();
    $fs->delete_area_files($context->id);

    $transaction = $DB->start_delegated_transaction();

    try {

        $posts = $DB->get_records('knowledgeshare_posts', array('mod_id' => $id));

        foreach ($posts as $post) {
            $DB->delete_records('knowledgeshare_comments', array('post_id' => $post->id));
        }

        $DB->delete_records('knowledgeshare_posts', array('mod_id' => $id));

        $DB->delete_records('knowledgeshare', array('id' => $id));

        $DB->commit_delegated_transaction($transaction);
    } catch (Exception $e) {
        $DB->rollback_delegated_transaction($transaction, $e);
        return false;
    }

    return true;
}

function knowledgeshare_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{

    $filename = $args[1];
    $itemid = $args[0];
    $filepath = "/$context->id/mod_knowledgeshare/$filearea/$itemid/$filename";
    $fs = get_file_storage();

    //$files = $fs->get_area_files($context, 'mod_knowledgeshare', $filearea);

    if (!$file = $fs->get_file_by_hash(sha1($filepath))) {
        send_file_not_found();
    } else {
        send_stored_file($file, 0, 0, true, array());
    }
}
