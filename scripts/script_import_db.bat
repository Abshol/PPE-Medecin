compact /u "C:\Users\emily\Desktop\file.txt" /I /Q
C:\wamp64\bin\mysql\mysql8.0.31\bin\mysql.exe -u root -p -e "create database if not exists MEDECINS;"
C:\wamp64\bin\mysql\mysql8.0.31\bin\mysql.exe -u root -p MEDECINS < ../savedb/dump_%date:/=%.sql