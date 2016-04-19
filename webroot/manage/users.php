<?php
include_once( '../../lib/input.phpm' );
include_once( '../../lib/security.phpm' );
include_once( '../../lib/output.phpm' );
include_once( '../../lib/user.phpm' );

authorize( 'manage_site' );

$users = all_users();
$roles = roles();

foreach ( $users as &$user ) {
  foreach ( $roles as $roleid => $arole ) {
    if ( $roleid == $user['role'] ) {
      $user['role'] = $arole['name'];
      break;
    }
  }
}

$output = array(
	'users' => $users,
);
output( $output, 'manage/users.tmpl' );
?>
