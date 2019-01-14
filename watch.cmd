set PUB_DIRECTORY=C:\xampp\htdocs

@call roots clean
@call roots compile

:remove all data in %PUB_DIRECTORY%
pushd %PUB_DIRECTORY%
del *.* /q
for /D %%f in ( * ) do rmdir /s "%%f" /q
popd

xcopy public %PUB_DIRECTORY% /E /I /Y

start http://localhost
pause