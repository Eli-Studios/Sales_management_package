@echo off
REM DDLess CLI Debug Wrapper (Windows)
REM
REM Usage: .ddless\php-ddless.cmd [--host=<ip>] <port> <script> [args...]
REM Example: .ddless\php-ddless.cmd 8001 artisan test --filter=UserTest
REM Example: .ddless\php-ddless.cmd --host=192.168.0.8 8001 artisan test
REM
REM Requires: curl (included in Windows 10+), and a DDLess listener active on the specified port.

setlocal EnableDelayedExpansion

REM Parse --host option
set "HOST=127.0.0.1"
set "ARG1=%~1"
if "!ARG1:~0,7!"=="--host=" (
  set "HOST=!ARG1:~7!"
  shift
)

if "%~2"=="" (
  echo Usage: .ddless\php-ddless.cmd [--host=^<ip^>] ^<port^> ^<script^> [args...] >&2
  echo Example: .ddless\php-ddless.cmd 8001 artisan test >&2
  echo Example: .ddless\php-ddless.cmd --host=192.168.0.8 8001 artisan test >&2
  exit /b 1
)

set "PORT=%~1"
shift

REM Build JSON args array
set "JSON_ARGS=["
set "FIRST=1"
:argloop
if "%~1"=="" goto argdone
set "ARG=%~1"
REM Escape backslashes and quotes for JSON
set "ARG=!ARG:\=\\!"
set "ARG=!ARG:"=\"!"
if defined FIRST (
  set "JSON_ARGS=!JSON_ARGS!\"!ARG!\""
  set "FIRST="
) else (
  set "JSON_ARGS=!JSON_ARGS!,\"!ARG!\""
)
shift
goto argloop
:argdone
set "JSON_ARGS=!JSON_ARGS!]"

set "BODY={\"args\":!JSON_ARGS!}"

REM Create temp file for response processing
set "TMPFILE=%TEMP%\ddless-cli-%RANDOM%.tmp"
set "EXITFILE=%TEMP%\ddless-exit-%RANDOM%.tmp"
echo 0 > "!EXITFILE!"

REM Make HTTP POST request with curl (streaming)
curl -sSN -X POST "http://!HOST!:!PORT!/__ddless/cli" -H "Content-Type: application/json" -d "!BODY!" --max-time 0 -o "!TMPFILE!" 2>nul

if errorlevel 1 (
  if not exist "!TMPFILE!" (
    echo Error: Could not connect to DDLess at !HOST!:!PORT!. >&2
    echo Make sure DDLess is running and the project listener is active. >&2
    if "!HOST!"=="127.0.0.1" (
      echo If running inside Docker/WSL, use --host to reach the host machine: >&2
      echo   .ddless\php-ddless.cmd --host=^<your-host-ip^> !PORT! ^<script^> >&2
    )
    del /q "!EXITFILE!" 2>nul
    exit /b 1
  )
)

REM Parse NDJSON response using PowerShell for reliable JSON handling
powershell -NoProfile -Command ^
  "$exitCode = 0; " ^
  "Get-Content -Path '%TMPFILE%' | ForEach-Object { " ^
  "  $line = $_.Trim(); " ^
  "  if ($line) { " ^
  "    try { " ^
  "      $obj = $line | ConvertFrom-Json; " ^
  "      switch ($obj.type) { " ^
  "        'stdout' { [Console]::Out.Write($obj.data) } " ^
  "        'stderr' { [Console]::Error.Write($obj.data) } " ^
  "        'breakpoint' { " ^
  "          [Console]::Out.WriteLine(''); " ^
  "          if ($obj.file -and $obj.line) { " ^
  "            [Console]::Out.WriteLine(('Breakpoint hit at {0}:{1}' -f $obj.file, $obj.line)) " ^
  "          } else { " ^
  "            [Console]::Out.WriteLine('Breakpoint hit') " ^
  "          }; " ^
  "          [Console]::Out.WriteLine([char]0x2192 + ' Paused in DDLess UI'); " ^
  "          [Console]::Out.WriteLine('') " ^
  "        } " ^
  "        'exit'   { $exitCode = $obj.code } " ^
  "      } " ^
  "    } catch {} " ^
  "  } " ^
  "}; " ^
  "$exitCode | Out-File -FilePath '%EXITFILE%' -NoNewline"

REM Read exit code
set /p EXIT_CODE=<"!EXITFILE!"

REM Cleanup
del /q "!TMPFILE!" 2>nul
del /q "!EXITFILE!" 2>nul

exit /b !EXIT_CODE!
