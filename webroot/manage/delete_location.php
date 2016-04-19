<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../lib/user.phpm' );

include_once( '../../inc/site.phpm' );

authorize( 'manage_site' );

$locationid = input( 'locationid', INPUT_PINT );
$op = input( 'op', INPUT_STR );

if ( $locationid ) {
  $locations = all_locations();
  foreach ( $locations as $loc ) {
    if ( $loc['locationid'] == $locationid ) {
      $location = $loc;
      break;
    }
  }
  if ( ! $location ) {
    $error[] = 'BADLOC';
  } elseif ( $op == "Delete" ) {
    delete_location( $locationid );
    $deleted = 1;
  }
}

$output = array(
	'location' => $location,
	'error' => $error,
	'deleted' => $deleted,
);
output( $output, 'manage/delete_location.tmpl' );
?>
