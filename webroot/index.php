<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'reports' );
$total = total_assets();

$output = array(
    'total' => $total,
);
output( $output, 'index' );
?>
