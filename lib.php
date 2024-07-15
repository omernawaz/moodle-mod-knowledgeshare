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
    return true;
}
