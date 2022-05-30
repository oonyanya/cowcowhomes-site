@call hexo clean
@call hexo gzip
"C:\Program Files (x86)\WinSCP\WinSCP.exe" %FTP_SERVER_HOST% /synchronize .\public /
.\gdrive.exe sync upload .\source\image\ 1gMOz4dX75PRZ4E1DyHftNw7xGZOKIO3w