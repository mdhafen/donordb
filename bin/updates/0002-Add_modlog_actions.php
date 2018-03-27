<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$table = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT COUNT(*) AS count FROM information_schema.columns WHERE table_schema = '$table' AND table_name = 'modlog_actions'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( ! $row['count'] ) {

  $query = 'CREATE TABLE `modlog_actions` ( `actionid` BIGINT(20) UNSIGNED NOT NULL, `field` VARCHAR(16) NOT NULL, `old_value` TEXT NULL DEFAULT NULL, `new_value` TEXT NULL DEFAULT NULL, `userid` INT(10) UNSIGNED NULL DEFAULT NULL, ipAddress VARBINARY(16) NOT NULL DEFAULT 2130706433, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, KEY (actionid), KEY (field) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
  $dbh->exec( $query );
  if ( $dbh->errorCode() != '00000' ) {
      print "Database error: ". $dbh->errorInfo()[2] ."\n";
  }

  return "Add modlog_actions table";
}

?>
