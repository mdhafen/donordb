<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../inc/donordb.phpm' );

authorize( 'contacts' );

$contactid = 0;
$contact = array();
$contact_details = '';

function xml_encode(&$val, $key) {
    $val = htmlspecialchars(htmlspecialchars_decode($val,ENT_QUOTES|ENT_HTML5),ENT_QUOTES|ENT_XML1|ENT_SUBSTITUTE);
}

$updates = array(
    'name' => input( 'name', INPUT_HTML_NONE ),
    'company' => input( 'company', INPUT_HTML_NONE ),
    'street' => input( 'street', INPUT_HTML_NONE ),
    'city' => input( 'city', INPUT_HTML_NONE ),
    'state' => input( 'state', INPUT_HTML_NONE ),
    'zip' => input( 'zip', INPUT_HTML_NONE ),
    'phone' => input( 'phone', INPUT_HTML_NONE ),
);

if ( !empty($updates) && !empty($updates['name']) ) {
    $contactid = update_contact( 0, $updates );
    if ( !empty($contactid) ) {
        $contact = get_contacts( array($contactid) )[$contactid];

        array_walk( $contact, 'xml_encode' );
        $contact_details .= "<contact><contactid>${contactid}</contactid><name>${contact['name']}</name><company>${contact['company']}</company><street>${contact['street']}</street><city>${contact['city']}</city><state>${contact['state']}</state><zip>${contact['zip']}</zip><phone>${contact['phone']}</phone></contact>";
    }
}

if ( !empty($contact_details) ) {
  output( '<?xml version="1.0"?><result><state>Success</state>'. $contact_details .'</result>' );
}
else {
    output( '<?xml version="1.0"?><result><state>Error</state><errors><messages><flag>CONTACT_SAVE_FAILED</flag><message>There was an error saving the contact.</message></messages></errors></result>' );
}
?>
