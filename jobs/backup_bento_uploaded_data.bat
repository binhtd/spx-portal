@echo off
set DATESTAMP=%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%
cd C:\wamp\www\fms-portal\public\uploads
mysqldump -u fms_portal -h localhost -pfms_portal --add-drop-table fms_portal > backup%DATESTAMP%.sql
cd C:\wamp\www\spx-portal\public\
xcopy uploads \\vn-t-binht\share\ /i /s /y /e

rem mysql -usqluser -p databasename </home/username/databasename.backup.sql
rem xcopy "desktop my network places\source folder\*.*" "c:\destination 
rem folder" /e /d /r /h /k /r /y 
rem xcopy "C:\BACKUP\ datefolder \" C:\ /E /F /H /K /X