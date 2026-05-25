@echo off
REM Servidor de desarrollo Laravel en Windows (usa php.ini completo con mbstring).
REM Si cambias la ruta de PHP por winget/actualizaciones, edita PHP_EXE y PHP_INI abajo.

set "ROOT=%~dp0"
set "PHP_EXE=C:\Users\jhose\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
set "PHP_INI=C:\Users\jhose\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.ini"

cd /d "%ROOT%public"
"%PHP_EXE%" -c "%PHP_INI%" -S 127.0.0.1:8000 "%ROOT%vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php"
