<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$table = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT COUNT(*) AS count FROM information_schema.columns WHERE table_schema = '$table' AND table_name = 'contacts' AND column_name = 'email'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( empty($row['count']) ) {

  $query = "ALTER TABLE `contacts` ADD COLUMN `email` VARCHAR(128) NULL DEFAULT NULL";
  $dbh->query($query);

  if ( $dbh->errorCode() != '00000' ) {
      print "Database error: ". $dbh->errorInfo()[2] ."\n";
  }

  return "Add email column to contact table";
}

?>
