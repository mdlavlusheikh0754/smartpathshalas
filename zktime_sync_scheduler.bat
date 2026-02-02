@echo off
title ZKTime.Net Bidirectional Sync Scheduler
echo ========================================
echo ZKTime.Net Bidirectional Sync Scheduler
echo ========================================
echo.
echo This script will sync data between Laravel and ZKTime.Net every 2 minutes.
echo Press Ctrl+C to stop the scheduler.
echo.

:loop
echo [%date% %time%] Running ZKTime.Net Bidirectional Sync...
php zktime_bidirectional_bridge.php

if %errorlevel% neq 0 (
    echo [%date% %time%] Sync failed with error code %errorlevel%
) else (
    echo [%date% %time%] Sync completed successfully
)

echo.
echo Waiting 2 minutes before next sync...
timeout /t 120 /nobreak > nul
echo.
goto loop