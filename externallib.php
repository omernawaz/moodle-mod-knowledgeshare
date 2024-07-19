<?php

/**
 * mod_knowledgeshare external file
 *
 * @package    component
 * @category   external
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//namespace local_message;


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class mod_knowledgeshare_external extends external_api
{

    public static function upvote_post_parameters()
    {
        return new external_function_parameters(
            [
                'postid' => new external_value(PARAM_INT, 'id of post to like'),
                'modid' => new external_value(PARAM_INT, 'id of cm where post exists')
            ]
        );
    }


    public static function upvote_post($postid, $modid)
    {
        global $CFG, $USER, $DB;
        require_once("$CFG->dirroot/group/lib.php");

        $params = self::validate_parameters(self::upvote_post_parameters(), array('postid' => $postid, 'modid' => $modid));

        [$course, $cm] = get_course_and_cm_from_cmid($modid, 'knowledgeshare');
        $context = \CONTEXT_MODULE::instance($cm->id);

        require_course_login($course, false, $cm);
        require_capability('mod/knowledgeshare:reply', $context, $USER->id);


        $already_upvoted = $DB->get_record('knowledgeshare_upvotes', ['post_id' => $postid, 'upvoter_id' => $USER->id]);

        $record = $DB->get_record('knowledgeshare_posts', ['id' => $postid]);

        if (!empty($already_upvoted)) {

            $record->upvotes -= 1;
            $DB->update_record('knowledgeshare_posts', $record);
            $DB->delete_records('knowledgeshare_upvotes', ['post_id' => $postid, 'upvoter_id' => $USER->id]);

            return json_encode(array(
                'status' => 'downvote',
                'upvotes' => $record->upvotes,
            ));
        }

        $record->upvotes += 1;
        $DB->update_record('knowledgeshare_posts', $record);

        $upvote = new stdClass();
        $upvote->post_id = $postid;
        $upvote->upvoter_id = $USER->id;
        $upvote->timemodified = time();


        $DB->insert_record('knowledgeshare_upvotes', $upvote);

        return json_encode(array(
            'status' => 'upvote',
            'upvotes' => $record->upvotes,
        ));
    }


    public static function upvote_post_returns()
    {
        return new external_value(PARAM_RAW, 'Returns updated count of the upvotes on the post.');
    }
}
