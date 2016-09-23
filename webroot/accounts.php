<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'accounts' );

$all_accounts = get_accounts_with_total();

$output = array(
    'accounts_list' => $all_accounts,
);
output( $output, 'accounts' );
?>
