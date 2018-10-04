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
$num_actions = 0;
if ( !empty($accountid) ) {
    $account = get_accounts( array($accountid) )[$accountid];
    $num_actions = get_actions_count_by_account( array($accountid) )[$accountid];
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
    ( $locationid = input('locationid',INPUT_PINT) ) || ( $locationid = null );
    $note = input( 'note', INPUT_HTML_NONE );
    $retired = !empty(input( 'retired', INPUT_STR )) ? 1 : 0;

    if ( empty($account['name']) || $account['name'] != $name ) {
        $updates['name'] = $name;
    }
    if ( !array_key_exists('locationid',$account) || $account['locationid'] != $locationid ) {
        $updates['locationid'] = $locationid;
    }
    if ( empty($account['note']) || $account['note'] != $note ) {
        $updates['note'] = $note;
    }
    if ( $account['retired'] != $retired ) {
        $updates['retired'] = $retired;
    }

    if ( !empty($updates) ) {
        $accountid = update_account( $accountid, $updates );
        if ( !empty($accountid) ) {
            $op = 'SaveSuccess';
            $account = get_accounts( array($accountid) )[$accountid];
            foreach ( $locations as &$loc ) {
                if ( $account['locationid'] == $loc['locationid'] ) {
                    $loc['selected'] = 1;
                }
                else {
                    unset($loc['selected']);
                }
            }
        }
    }
}
else if ( $op == 'Delete' ) {
    if ( $num_actions === 0 ) {
        delete_account($accountid);
        $op = 'DeleteSuccess';
    }
    else {
        $op = 'DeleteFail';
    }
}

$output = array(
    'locations' => $locations,
    'accountid' => $accountid,
    'account' => $account,
    'num_actions' => $num_actions,
    'op' => $op,
);
output( $output, 'editaccount' );
?>
