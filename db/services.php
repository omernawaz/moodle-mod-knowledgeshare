<?php
$functions = array(
    'mod_knowledgeshare_upvote_post' => array(
        'classname' => 'mod_knowledgeshare_external',
        'methodname' => 'upvote_post',
        'classpath' => '/mod/knowledgeshare/externallib.php',
        'description' => 'upvotes a post by id',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/knowledgeshare:reply',
    ),
    'mod_knowledgeshare_add_reply' => array(
        'classname' => 'mod_knowledgeshare_external',
        'methodname' => 'add_reply',
        'classpath' => '/mod/knowledgeshare/externallib.php',
        'description' => 'adds a reply to a post by id',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/knowledgeshare:reply',
    ),
    'mod_knowledgeshare_delete_post_or_reply' => array(
        'classname' => 'mod_knowledgeshare_external',
        'methodname' => 'delete_post_or_reply',
        'classpath' => '/mod/knowledgeshare/externallib.php',
        'description' => 'deletes a post or reply',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/knowledgeshare:createpost',
    )
);
