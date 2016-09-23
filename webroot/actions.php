<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'actions' );

$recent_actions = get_actions_since_cross( '-1 year' );

$output = array(
    'actions_list' => $recent_actions,
);
output( $output, 'actions' );
?>
