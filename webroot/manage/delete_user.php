<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../lib/user.phpm' );

authorize( 'manage_site' );

$userid = input( 'userid', INPUT_PINT );
$op = input( 'op', INPUT_STR );
$deleted = 0;

if ( $userid ) {
  $user = user_by_userid( $userid );
  if ( ! $user ) {
    error( array('BADUSER'=>1) );
  } elseif ( $op == "Delete" ) {
    delete_user( $userid );
    $deleted = 1;
  }
}

$output = array(
	'user' => $user,
	'deleted' => $deleted,
);
output( $output, 'manage/delete_user.tmpl' );
?>
