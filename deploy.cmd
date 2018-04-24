@call roots clean
@call roots compile -e production
"C:\Program Files (x86)\WinSCP\WinSCP.exe" %FTP_SERVER_HOST% /synchronize %USERPROFILE%\cowcowhomes-site\public /
