@call roots clean
@call roots compile -e production
"C:\Program Files (x86)\WinSCP\WinSCP.exe" %FTP_SERVER_HOST% /synchronize %USERPROFILE%\cowcowhomes-site\public /
.\gdrive-windows-x64.exe sync upload .\assets\img\ 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w