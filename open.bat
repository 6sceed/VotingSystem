@echo off
:: Change this path to where Chrome is installed if needed
set CHROME_PATH="C:\Program Files\Google\Chrome\Application\chrome.exe"

:: URL to open
set URL=http://localhost/voting_system/index.html

:: Open Chrome with the URL
start "" %CHROME_PATH% %URL%
