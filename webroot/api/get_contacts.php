<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../inc/donordb.phpm' );

authorize( 'contacts' );

$contacts = get_contacts();
$contact_details = '';

uasort( $contacts, function($a,$b){ return strcasecmp($a['name'],$b['name']); } );

function xml_encode(&$val, $key) {
    $val = htmlspecialchars(htmlspecialchars_decode($val,ENT_QUOTES|ENT_HTML5),ENT_QUOTES|ENT_XML1|ENT_SUBSTITUTE);
}

foreach ( $contacts as $con ) {
    array_walk( $con, 'xml_encode' );
    $contact_details .= "<contact><contactid>". $con['contactid'] ."</contactid><name>${con['name']}</name><company>${con['company']}</company><street>${con['street']}</street><city>${con['city']}</city><state>${con['state']}</state><zip>${con['zip']}</zip><phone>${con['phone']}</phone></contact>";
}

if ( !empty($contacts) ) {
  output( '<?xml version="1.0"?><result><state>Success</state>'. $contact_details .'</result>' );
}
else {
    output( '<?xml version="1.0"?><result><state>Error</state><errors><messages><flag>CONTACTS_EMPTY</flag><message>There were no contacts found</message></messages></errors></result>' );
}
?>
