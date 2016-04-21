<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$all_actions = get_actions_cross();

$output = array(
    'actions_list' => $all_actions,
);
output( $output, 'actions' );
?>
