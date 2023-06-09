-- @OLD_DB='fdonordb', @NEW_DB='fdonordb_v2';
-- @WCSD_USER=164;

INSERT INTO fdonordb_v2.location (locationid,name) (SELECT schoolId,name FROM fdonordb.locations);

INSERT INTO fdonordb_v2.accounts (accountid,name,locationid,note) (SELECT accountId,replace(replace(name,'&','&amp;'),'''','&apos;'),IF( schoolId IN (0,15,702,1476), NULL, schoolId),CONVERT(replace(replace(note,'&','&amp;'),'''','&apos;') USING BINARY) FROM fdonordb.accounts);

INSERT INTO fdonordb_v2.contacts (contactid,name,company,street,city,state,zip,phone) (SELECT contactId,replace(replace(name,'&','&amp;'),'''','&apos;'),replace(replace(company,'&','&amp;'),'''','&apos;'),street,city,state,zip,phone FROM fdonordb.contacts);

INSERT IGNORE INTO fdonordb_v2.actions (actionid,date,amount,contactid,accountid,locationid,receipt,po,note,timestamp,udate) (SELECT actionId,IF(INSTR(DATE,'-'),STR_TO_DATE(date,'%Y-%m-%d'),STR_TO_DATE(date,'%m/%d/%Y')),amount,IF( contactId IN (0,628,1161,2111,2112,2113,3259,6661,11684,13363,13911,14763,17936,22097),NULL,contactId),IF( accountId IN (11,904,1041,1439,3224,3661),NULL,accountId),IF( schoolId IN (0,15,702,1476),NULL,schoolId),receipt,replace(replace(po,'&','&amp;'),'''','&apos;'),replace(replace(note,'&','&amp;'),'''','&apos;'),timestamp,FROM_UNIXTIME(udate) FROM fdonordb.actions);

UPDATE fdonordb_v2.actions SET date = '2007-4-27' WHERE actionid = 6076;
UPDATE fdonordb_v2.actions SET date = '2007-5-23' WHERE actionid = 6249;
UPDATE fdonordb_v2.actions SET date = '2007-10-5' WHERE actionid = 7017;
UPDATE fdonordb_v2.actions SET date = '2008-8-7' WHERE actionid = 8381;
UPDATE fdonordb_v2.actions SET date = '2007-9-13' WHERE actionid = 8754;
UPDATE fdonordb_v2.actions SET date = '2009-4-28' WHERE actionid = 9600;
UPDATE fdonordb_v2.actions SET date = '2009-7-25' WHERE actionid = 10093;
UPDATE fdonordb_v2.actions SET date = '2009-10-14' WHERE actionid = 10318;
UPDATE fdonordb_v2.actions SET date = '2009-12-4' WHERE actionid = 10593;
UPDATE fdonordb_v2.actions SET date = '2009-5-12' WHERE actionid = 10653;
UPDATE fdonordb_v2.actions SET date = '2010-3-17' WHERE actionid = 11077;
UPDATE fdonordb_v2.actions SET date = '2010-9-17' WHERE actionid = 11089;
UPDATE fdonordb_v2.actions SET date = '2009-12-31' WHERE actionid = 11128;
UPDATE fdonordb_v2.actions SET date = '2010-3-17' WHERE actionid = 11176;
UPDATE fdonordb_v2.actions SET date = '2010-3-22' WHERE actionid = 11195;
UPDATE fdonordb_v2.actions SET date = '2010-6-30' WHERE actionid = 11756;
UPDATE fdonordb_v2.actions SET date = '2011-1-7' WHERE actionid = 12463;
UPDATE fdonordb_v2.actions SET date = '2009-1-7' WHERE actionid = 12623;
UPDATE fdonordb_v2.actions SET date = '2011-9-22' WHERE actionid = 13599;
UPDATE fdonordb_v2.actions SET date = '2011-10-5' WHERE actionid = 13640;
UPDATE fdonordb_v2.actions SET date = '2011-12-9' WHERE actionid = 13783;
UPDATE fdonordb_v2.actions SET date = '2011-12-7' WHERE actionid = 13803;
UPDATE fdonordb_v2.actions SET date = '2012-2-16' WHERE actionid = 14545;
UPDATE fdonordb_v2.actions SET date = '2011-9-4' WHERE actionid = 15035;
UPDATE fdonordb_v2.actions SET date = '2011-10-26' WHERE actionid = 15071;
UPDATE fdonordb_v2.actions SET date = '2012-9-4' WHERE actionid = 15429;
UPDATE fdonordb_v2.actions SET date = '2012-12-31' WHERE actionid = 16022;
UPDATE fdonordb_v2.actions SET date = '2012-11-7' WHERE actionid = 16165;
UPDATE fdonordb_v2.actions SET date = '2013-1-22' WHERE actionid = 16269;
UPDATE fdonordb_v2.actions SET date = '2013-3-7' WHERE actionid = 16481;
UPDATE fdonordb_v2.actions SET date = '2013-5-1' WHERE actionid = 16744;
UPDATE fdonordb_v2.actions SET date = '2013-11-2' WHERE actionid = 16986;
UPDATE fdonordb_v2.actions SET date = '2013-6-30' WHERE actionid IN ( 17845, 17849 );
UPDATE fdonordb_v2.actions SET date = '2013-10-16' WHERE actionid = 17928;
UPDATE fdonordb_v2.actions SET date = '2013-10-21' WHERE actionid IN ( 17930, 17932 );
UPDATE fdonordb_v2.actions SET date = '2013-10-28' WHERE actionid IN ( 17942, 17944 );
UPDATE fdonordb_v2.actions SET date = '2014-2-28' WHERE actionid = 18274;
UPDATE fdonordb_v2.actions SET date = '2014-3-28' WHERE actionid = 18295;
UPDATE fdonordb_v2.actions SET date = '2014-5-7' WHERE actionid = 18375;
UPDATE fdonordb_v2.actions SET date = '2014-4-14' WHERE actionid = 18437;
UPDATE fdonordb_v2.actions SET date = '2014-3-28' WHERE actionid = 18589;
UPDATE fdonordb_v2.actions SET date = '2014-3-9' WHERE actionid = 18703;
UPDATE fdonordb_v2.actions SET date = '2014-6-30' WHERE actionid IN ( 18894, 18931 );
UPDATE fdonordb_v2.actions SET date = '2014-8-7' WHERE actionid IN ( 19322, 19323 );
UPDATE fdonordb_v2.actions SET date = '2015-1-28' WHERE actionid = 19894;
UPDATE fdonordb_v2.actions SET date = '2015-4-28' WHERE actionid = 20226;
UPDATE fdonordb_v2.actions SET date = '2015-5-1' WHERE actionid = 20236;
UPDATE fdonordb_v2.actions SET date = '2015-3-4' WHERE actionid = 20462;
UPDATE fdonordb_v2.actions SET date = '2014-12-4' WHERE actionid = 20722;
UPDATE fdonordb_v2.actions SET date = '2014-12-31' WHERE actionid = 20754;
UPDATE fdonordb_v2.actions SET date = '2014-6-11' WHERE actionid = 20016;
UPDATE fdonordb_v2.actions SET date = '2015-11-2' WHERE actionid = 21319;
UPDATE fdonordb_v2.actions SET date = '2015-12-14' WHERE actionid = 21586;
UPDATE fdonordb_v2.actions SET date = '2015-9-16' WHERE actionid = 21787;
UPDATE fdonordb_v2.actions SET date = '2015-10-6' WHERE actionid = 21846;
UPDATE fdonordb_v2.actions SET date = '2015-10-28' WHERE actionid = 21847;

UPDATE fdonordb_v2.actions SET is_transfer = 1 WHERE contactid = 164 AND ( po LIKE 'transfer' OR note LIKE 'transfer%' );

INSERT INTO fdonordb_v2.user (username,fullname,password,role,salt) (SELECT username,realname,password,CASE privilege WHEN 63 THEN 2 WHEN 255 THEN 3 ELSE 0 END,'' FROM fdonordb.users);
