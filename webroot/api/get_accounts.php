<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../inc/donordb.phpm' );

authorize( 'accounts' );

$locationid = input( 'locationid', INPUT_PINT );
if ( !empty($locationid) ) {
    $accounts = get_accounts_at_location( $locationid );
}
else {
    $accounts = get_accounts();
}
$account_details = '';

uasort( $accounts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

foreach ( $accounts as $acc ) {
    $account_details .= "<account><accountid>". $acc['accountid'] ."</accountid><name>". htmlspecialchars(htmlspecialchars_decode($acc['name'],ENT_QUOTES|ENT_HTML5),ENT_QUOTES|ENT_XML1|ENT_SUBSTITUTE) ."</name></account>";
}

if ( !empty($accounts) ) {
  output( '<?xml version="1.0"?><result><state>Success</state>'. $account_details .'</result>' );
}
else {
    output( '<?xml version="1.0"?><result><state>Error</state><errors><messages><flag>ACCOUNTS_EMPTY</flag><message>There were no accounts found at that location</message></messages></errors></result>' );
}
?>
