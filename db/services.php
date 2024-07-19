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
    )
);
