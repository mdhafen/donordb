<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'reports' );

function is_retired($r) { return $r['retired']; }

$total = total_assets();
$retired_accounts = get_accounts_with_total([],true);
$retired_accounts = array_filter($retired_accounts,"is_retired");
$retired_total = array_sum(array_column( $retired_accounts,'total'));

$output = array(
    'total' => $total,
    'retired_total' => $retired_total,
);
output( $output, 'index' );
?>
