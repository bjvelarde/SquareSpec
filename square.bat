@echo off
set command=php %~dp0\square.php
set "errmsg=Usage: square ^(<spec-file^>^)"
IF [%1] NEQ [] (
  %command% %1
) ELSE (
  %command%
)