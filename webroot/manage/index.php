<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../lib/user.phpm' );

authorize('manage_site');

$output = array(
);
output( $output, 'manage/index.tmpl' );
?>
