@echo off
set INI_FILE="deploy.ini"
rem deply.iniは直接書かないとうまく動かない。%INI_FILE%と合わせること
for /f "eol=;tokens=1,2 delims==" %%i in (deploy.ini) do (
  set %%i=%%j
)
if not defined FTP_SERVER_HOST (
  echo FTP_SERVER_HOST=DUMMY > %INI_FILE%
  echo "you must to fill %INI_FILE%"
  exit
)
@call build.cmd
"C:\Program Files (x86)\WinSCP\WinSCP.exe" /console /script=deploy_web.txt /parameter %FTP_SERVER_HOST% %~dp0%/public
