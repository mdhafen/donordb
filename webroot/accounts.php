<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'accounts' );

//$include_retired = !empty(input( 'retired', INPUT_STR )) ? 1 : 0;

$all_accounts = get_accounts_with_total( [], true ); //$include_retired );

$output = array(
    'accounts_list' => $all_accounts,
    'retired' => $include_retired,
);
output( $output, 'accounts' );
?>
