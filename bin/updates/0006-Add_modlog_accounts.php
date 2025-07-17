<?php

include_once( '../../lib/config.phpm' );
include_once( '../../lib/data.phpm' );

$db_settings = $config['database']['core']['write'];
$table = $db_settings['schema'];

$dbh = db_connect();
$query = "SELECT COUNT(*) AS count FROM information_schema.columns WHERE table_schema = '$table' AND table_name = 'modlog_accounts'";
$sth = $dbh->query( $query );
$row = $sth->fetch();
if ( ! $row['count'] ) {

  $query = 'CREATE TABLE `modlog_accounts` ( `accountid` BIGINT(20) UNSIGNED NOT NULL, `field` VARCHAR(16) NOT NULL, `old_value` TEXT NULL DEFAULT NULL, `new_value` TEXT NULL DEFAULT NULL, `userid` INT(10) UNSIGNED NULL DEFAULT NULL, ipAddress VARBINARY(16) NOT NULL DEFAULT 2130706433, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, KEY (accountid), KEY (field) ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
  $dbh->exec( $query );
  if ( $dbh->errorCode() != '00000' ) {
      print "Database error: ". $dbh->errorInfo()[2] ."\n";
  }

  $query = 'INSERT INTO modlog_accounts (accountid,field,old_value,new_value,userid,timestamp) (SELECT a1.accountid, "locationid", a1.locationid, a2.locationid, 6, a1.udate FROM actions AS a1 LEFT JOIN actions AS a2 ON (a2.accountid = a1.accountid AND a2.amount = a1.amount * -1 AND date(a2.udate) = date(a1.udate) AND a2.locationid <> a1.locationid ) LEFT JOIN accounts ON (accounts.accountid = a1.accountid) WHERE a1.in_kind = 0 AND a1.locationid <> accounts.locationid AND a1.is_transfer = 1 AND a2.actionid IS NOT NULL )';
  $dbh->exec( $query );

  return "Add and fill modlog_accounts table";
}

?>
