set PUB_DIRECTORY=C:\xampp\htdocs

call hexo clean
call hexo gzip
call node web_fetch.js

:remove all data in %PUB_DIRECTORY%
pushd %PUB_DIRECTORY%
del *.* /q
for /D %%f in ( * ) do rmdir /s "%%f" /q
popd

xcopy .\public %PUB_DIRECTORY% /E /I /Y

start http://localhost
pause