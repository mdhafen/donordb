<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$schema = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT COUNT(*) AS count FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = 'accounts' AND column_name = 'retired'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( ! $row['count'] ) {

  $query = 'ALTER TABLE `accounts` ADD COLUMN `retired` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0';
  $dbh->exec( $query );
  if ( $dbh->errorCode() != '00000' ) {
      print "Database error: ". $dbh->errorInfo()[2] ."\n";
  }

  return "Add retired column to accounts table";
}

?>
