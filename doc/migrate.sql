-- @OLD_DB='fdonordb', @NEW_DB='fdonordb_v2';

INSERT INTO fdonordb_v2.location (locationid,name) (SELECT schoolId,name FROM fdonordb.locations);

INSERT INTO fdonordb_v2.accounts (accountid,name,locationid,note) (SELECT accountId,name,IF( schoolId IN (0,15,702,1476), NULL, schoolId),CONVERT(note USING BINARY) FROM fdonordb.accounts);

INSERT INTO fdonordb_v2.contacts (contactid,name,company,street,city,state,zip,phone) (SELECT contactId,name,company,street,city,state,zip,phone FROM fdonordb.contacts);

INSERT INTO fdonordb_v2.actions (actionid,date,amount,contactid,accountid,locationid,receipt,po,note,timestamp,udate) (SELECT actionId,IF(INSTR(DATE,'-'),STR_TO_DATE(date,'%Y-%m-%d'),STR_TO_DATE(date,'%m/%d/%Y')),amount,IF( contactId IN (0,628,1161,2111,2112,2113,3259,6661,11684,13363,13911,14763,17936,22097),NULL,contactId),IF( accountId IN (11,904,1041,1439,3224,3661),NULL,accountId),IF( schoolId IN (0,15,702,1476),NULL,schoolId),receipt,po,note,timestamp,FROM_UNIXTIME(udate) FROM fdonordb.actions);
