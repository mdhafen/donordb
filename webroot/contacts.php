<?php
include_once( '../lib/input.phpm' );
include_once( '../lib/security.phpm' );
include_once( '../lib/output.phpm' );
include_once( '../inc/donordb.phpm' );

authorize( 'contacts' );

$contacts = get_contacts();

$output = array(
    'contacts_list' => $contacts,
);
output( $output, 'contacts' );
?>
