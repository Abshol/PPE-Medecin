C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe -u root -proot glpi > ../savedb/dump_%date:/=%.sql
compact /c C:\wamp64\bin\savedb\dump_%date:/=%.sql /I /Q