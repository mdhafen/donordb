-- @OLD_DB='fdonordb', @NEW_DB='fdonordb_v2';

INSERT INTO fdonordb_v2.location (locationid,name) (SELECT schoolId,name FROM fdonordb.locations);

INSERT INTO fdonordb_v2.accounts (accountid,name,locationid,note) (SELECT accountId,name,IF( schoolId IN (0,15,702,1476), NULL, schoolId),CONVERT(note USING BINARY) FROM fdonordb.accounts);

INSERT INTO fdonordb_v2.contacts (contactid,name,company,street,city,state,zip,phone) (SELECT contactId,name,company,street,city,state,zip,phone FROM fdonordb.contacts);

INSERT INTO fdonordb_v2.actions (actionid,date,amount,contactid,accountid,locationid,receipt,po,note,timestamp,udate) (SELECT actionId,IF(INSTR(DATE,'-'),STR_TO_DATE(date,'%Y-%m-%d'),STR_TO_DATE(date,'%m/%d/%Y')),amount,IF( contactId IN (0,628,1161,2111,2112,2113,3259,6661,11684,13363,13911,14763,17936,22097),NULL,contactId),IF( accountId IN (11,904,1041,1439,3224,3661),NULL,accountId),IF( schoolId IN (0,15,702,1476),NULL,schoolId),receipt,po,note,timestamp,FROM_UNIXTIME(udate) FROM fdonordb.actions);

UPDATE fdonordb_v2.actions SET date = '5/12/2000' WHERE actionid = 10653;
UPDATE fdonordb_v2.actions SET date = '11/2/2013' WHERE actionid = 16986;
UPDATE fdonordb_v2.actions SET date = '6/30/2013' WHERE actionid = 17845;
UPDATE fdonordb_v2.actions SET date = '10/16/2013' WHERE actionid = 17928;
UPDATE fdonordb_v2.actions SET date = '10/21/2013' WHERE actionid = 17930;
UPDATE fdonordb_v2.actions SET date = '10/21/2013' WHERE actionid = 17932;
UPDATE fdonordb_v2.actions SET date = '10/28/2013' WHERE actionid = 17942;
UPDATE fdonordb_v2.actions SET date = '10/28/2013' WHERE actionid = 17944;
UPDATE fdonordb_v2.actions SET date = '8/7/2014' WHERE actionid = 19323;
UPDATE fdonordb_v2.actions SET date = '12/31/2014' WHERE actionid = 20754;
UPDATE fdonordb_v2.actions SET date = '6/11/2014' WHERE actionid = 20016;
UPDATE fdonordb_v2.actions SET date = '9/16/2015' WHERE actionid = 21787;
UPDATE fdonordb_v2.actions SET date = '10/6/2015' WHERE actionid = 21846;
UPDATE fdonordb_v2.actions SET date = '10/28/2015' WHERE actionid = 21847;
