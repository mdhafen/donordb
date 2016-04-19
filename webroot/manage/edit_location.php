<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../lib/user.phpm' );

authorize( 'manage_site' );

$locationid = input( 'locationid', INPUT_PINT );
$op = input( 'op', INPUT_STR );

$locations = all_locations();
$edit = 0;
$saved = 0;
$location = array();
$error = array();

if ( $locationid ) {
  foreach ( $locations as $loc ) {
    if ( $loc['locationid'] == $locationid ) {
      $location = $loc;
      break;
    }
  }

  if ( $location ) {
    $edit = 1;
  }
}

if ( $op == "Save" ) {  // Update/Add the location
  $newlocationid = input( 'new_locationid', INPUT_PINT );
  $name = input( 'name', INPUT_HTML_NONE );

  if ( $newlocationid != $locationid ) {
    foreach ( $locations as $loc ) {
      if ( $loc['locationid'] == $newlocationid ) {
	$error[] = "LOCIDTAKEN";
	break;
      }
    }
  }

  if ( empty($error) ) {

    if ( !empty($location) ) {
      if ( $newlocationid != $location['locationid'] ) {
	$updated['locationid'] = $newlocationid;
      }
      if ( $name != $location['name'] ) {
	$updated['name'] = $name;
      }
    } else {
      $updated = array(
	'locationid' => $newlocationid,
	'name' => $name,
		       );
    }

    if ( $updated ) {
      $locationid = update_location( $locationid, $updated );
      $saved = 1;

      $locations = all_locations();
      foreach ( $locations as $loc ) {
	if ( $loc['locationid'] == $newlocationid ) {
	  $location = $loc;
	  $edit = 1;
	  break;
	}
      }
    }
  }
}

$output = array(
	'edit' => $edit,
	'saved' => $saved,
	'location' => $location,
	'error' => $error,
);
output( $output, 'manage/edit_location.tmpl' );
?>
