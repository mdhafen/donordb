<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'accounts' );

$op = input( 'op', INPUT_STR );
$accountid = input( 'accountid', INPUT_PINT );
$locations = all_locations();

uasort( $locations, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );
$account = array();
if ( !empty($accountid) ) {
    $account = get_accounts( array($accountid) )[$accountid];
    foreach ( $locations as &$loc ) {
        if ( $account['locationid'] == $loc['locationid'] ) {
            $loc['selected'] = 1;
            break;
        }
    }
}

if ( $op == 'Save' ) {
    $updates = array();

    $name = input( 'name', INPUT_HTML_NONE );
    $locationid = input( 'locationid', INPUT_PINT );
    $note = input( 'note', INPUT_HTML_NONE );

    if ( empty($account['name']) || $account['name'] != $name ) {
        $updates['name'] = $name;
    }
    if ( empty($account['locationid']) || $account['locationid'] != $locationid ) {
        $updates['locationid'] = $locationid;
    }
    if ( empty($account['note']) || $account['note'] != $note ) {
        $updates['note'] = $note;
    }

    if ( !empty($updates) ) {
        $accountid = update_account( $accountid, $updates );
        if ( !empty($accountid) ) {
            $op = 'SaveSuccess';
            $account = get_accounts( array($accountid) )[$accountid];
        }
    }
}

$output = array(
    'locations' => $locations,
    'accountid' => $accountid,
    'account' => $account,
    'op' => $op,
);
output( $output, 'editaccount' );
?>
