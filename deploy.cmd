@call build.cmd
"C:\Program Files (x86)\WinSCP\WinSCP.exe" /console /script=deploy_web.txt /parameter %FTP_SERVER_HOST% %~dp0%/public
.\gdrive.exe sync upload .\source\image\ 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w