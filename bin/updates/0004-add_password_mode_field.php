<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$table = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT COUNT(*) AS count FROM information_schema.columns WHERE table_schema = '$table' AND table_name = 'user' AND column_name = 'password_mode'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( empty($row['count']) ) {

  $query = "ALTER TABLE `user` ADD COLUMN `password_mode` VARCHAR(32) NOT NULL DEFAULT ''";
  $dbh->query($query);

  if ( $dbh->errorCode() != '00000' ) {
      print "Database error: ". $dbh->errorInfo()[2] ."\n";
  }

  return "Add password_mode column to user table";
}

?>
