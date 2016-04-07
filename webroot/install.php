<?php
include_once( "../lib/config.pm" );
include_once( "../lib/input.php" );
include_once( "../lib/data.php" );
include_once( "../lib/security.php" );
include_once( "../lib/output.php" );

global $config;

$output = array();
last( $config['role'] );
$admin_role = key( $config['role'] );

$output = do_checks( $admin_role );

if ( !empty($output) ) {
    $output[ 'ERROR' ] = 1;
}
else {
    $locationid = input( 'locationid', INPUT_PINT );
    $locationname = input( 'locationname', INPUT_HTML_NONE );
    $username = input( 'username', INPUT_HTML_NONE );
    $password = input( 'password', INPUT_STR );
    $con_pass = input( 'password_confirm', INPUT_STR );
    $fullname = input( 'fullname', INPUT_HTML_NONE );
    $email = input( 'email', INPUT_EMAIL );

    $dbh = db_connect();
    $query = "SELECT COUNT(*) AS count FROM locations WHERE locationid = :lid";
    $sth = $dbh->prepare( $query );
    $sth->bindValue( ':lid', $locationid );
    $sth->execute();
    $result = $sth->fetchColumn();
    if ( $result >= 1 ) {
	# ALREADY_ADDED_LOCATION
    }
    else if ( ! ( $locationid && $locationname ) ) {
	$output['ERROR'] = 1;
	$output['INSTALL_NO_LOCATION'] = 1;
    }
    else {
        $location = array(
	    'locationid' => $locationid,
	    'locationname' => $locationname,
                           );
	$result = update_location( 0, $location );
	if ( empty($result) ) {
	    $output[ 'INSTALL_ADD_LOCATION_FAILED' ] = 1;
	    $output['ERROR'] = 1;
	}
    }

    if ( empty($output) && !empty($username) && !empty($password) && !empty($con_pass) ) {
      if ( strcmp($password,$con_pass) != 0 ) {
	    $output['INSTALL_PASS_NOMATCH'] = 1;
	    $output['ERROR'] = 1;
      }
      else {
	    list( $password, $salt ) = new_password( $con_pass );
	    $user = array(
		'username' => $username,
                'fullname' => !empty($fullname) ? $fullname : "",
                'email' => !empty($email) ? $email : "",
		'role' => $admin_role,
		'password' => $password,
		'salt' => $salt,
	    );
	    $result = update_user( 0, $user );
            if ( empty($result) ) {
		$output['INSTALL_ADD_USER_FAILED'] = 1;
		$output['ERROR'] = 1;
            }
            else {
              update_user_locations( $result, array( $locationid ) );
              $output[ 'INSTALL_DONE' ] = 1;
            }
	}
    }

    $output['locationid'] = $locationid;
    $output['locationname'] = $locationname;
    $output['username'] = $username;
    $output['fullname'] = $fullname;
    $output['email'] = $email;

}

output( $output, 'install' );

function do_checks( $admin_role ) {
    global $config;
    $dbh = db_connect();
    $output = array();
    $schema = $config['database']['core']['write']['schema'];

    if ( !empty($config['user_external_module']) ) {
	$output[ 'INSTALL_USER_EXTERNAL' ] = 1;
    }

    $query = "SELECT COUNT(*) AS count FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?";
    $sth = $dbh->prepare( $query );
    $sth->execute( $schema );
    $result = $sth->fetchColumn();
    if ( $result == 0 ) {
	$output[ 'INSTALL_NO_TABLES' ] = 1;

	if ( is_readable('../docs/install.sql') ) {
            $sql = file_get_contents( "../docs/install.sql" );
	    foreach ( explode(';',$sql) as $statement ) {
		if ( !empty($statement) ) {
		    $dbh->exec( $statement );
                    if ( $dbh->errorCode() !== '00000' ) {
			$output[ 'INSTALL_CREATETABLES_FAILED' ] = 1;
			return $output;
		    }
		}
	    }
	}
	else {
	    $output[ 'INSTALL_CREATESQL_CANT_READ' ] = 1;
	    return $output;
	}
    }

    $query = "SELECT COUNT(*) AS count FROM users WHERE role = ?";
    $sth = $dbh->prepare( $query );
    $sth->execute( $admin_role );
    $result = $sth->fetchColumn();
    if ( $result >= 1 ) {
	$output[ 'INSTALL_ALREADY_ADDED_ADMIN' ] = 1;
    }
    return $output;
}
