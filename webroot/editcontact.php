<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'contacts' );

$op = input( 'op', INPUT_STR );
$contactid = input( 'contactid', INPUT_PINT );

$contact = array();
if ( !empty($contactid) ) {
    $contact = get_contacts( array($contactid) )[$contactid];
}

if ( $op == 'Save' ) {
    $updates = array();

    $name = input( 'name', INPUT_HTML_NONE );
    $company = input( 'company', INPUT_HTML_NONE );
    $street = input( 'street', INPUT_HTML_NONE );
    $city = input( 'city', INPUT_HTML_NONE );
    $state = input( 'state', INPUT_HTML_NONE );
    $zip = input( 'zip', INPUT_HTML_NONE );
    $phone = input( 'phone', INPUT_HTML_NONE );

    if ( empty($contact['name']) || $contact['name'] != $name ) {
        $updates['name'] = $name;
    }
    if ( empty($contact['company']) || $contact['company'] != $company ) {
        $updates['company'] = $company;
    }
    if ( empty($contact['street']) || $contact['street'] != $street ) {
        $updates['street'] = $street;
    }
    if ( empty($contact['city']) || $contact['city'] != $city ) {
        $updates['city'] = $city;
    }
    if ( empty($contact['state']) || $contact['state'] != $state ) {
        $updates['state'] = $state;
    }
    if ( empty($contact['zip']) || $contact['zip'] != $zip ) {
        $updates['zip'] = $zip;
    }
    if ( empty($contact['phone']) || $contact['phone'] != $phone ) {
        $updates['phone'] = $phone;
    }

    if ( !empty($updates) ) {
        $contactid = update_contact( $contactid, $updates );
        if ( !empty($contactid) ) {
            $op = 'SaveSuccess';
            $contact = get_contacts( array($contactid) )[$contactid];
        }
    }
}

$output = array(
    'contactid' => $contactid,
    'contact' => $contact,
    'op' => $op,
);
output( $output, 'editcontact' );
?>
