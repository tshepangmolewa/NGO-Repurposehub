@echo off

 set dbUser=root
 set dbPassword=password
 set backupDir="F:\AdityaSatrioNugroho\testbackupDb-bat\"
 set mysqldump="C:\xampp\mysql\bin\mysqldump.exe"
 set mysqlDataDir="C:\xampp\mysql\data"
 set zip="C:\Program Files\7-Zip\7z.exe"

 :: get date
 for /F "tokens=2-4 delims=/ " %%i in ('date /t') do (
      set yy=%%i
      set mon=%%j
      set dd=%%k
 )

 :: get time
 for /F "tokens=5-8 delims=:. " %%i in ('echo.^| time ^| find "current" ') do (
      set hh=%%i
      set min=%%j
 )

 echo dirName=%yy%%mon%%dd%_%hh%%min%
 set dirName=%yy%%mon%%dd%_%hh%%min%
 
 :: switch to the "data" folder
 pushd %mysqlDataDir%

 :: iterate over the folder structure in the "data" folder to get the databases
 for /d %%f in (*) do (

 if not exist %backupDir%\%dirName%\ (
      mkdir %backupDir%\%dirName%
 )

 %mysqldump% --host="localhost" --user=%dbUser% --password=%dbPassword% --single-transaction --add-drop-table --databases %%f > %backupDir%\%dirName%\%%f.sql

 %zip% a -tgzip %backupDir%\%dirName%\%%f.sql.gz %backupDir%\%dirName%\%%f.sql

 del %backupDir%\%dirName%\%%f.sql
 )
 popd