$phpVersion = "8.2.20"
$zipName = "php-$phpVersion-nts-Win32-vs16-x64.zip"
$url = "https://windows.php.net/downloads/releases/archives/$zipName"
$desktopDir = $PSScriptRoot
$phpDir = Join-Path $desktopDir "..\php"
$zipPath = Join-Path $desktopDir "..\$zipName"

if (!(Test-Path $phpDir)) {
    New-Item -ItemType Directory -Force -Path $phpDir | Out-Null
}

if (!(Test-Path (Join-Path $phpDir "php.exe"))) {
    Write-Host "Downloading PHP $phpVersion from $url..."
    # Set TLS 1.2/1.3 protocol just in case
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12 -bor [Net.SecurityProtocolType]::Tls13
    
    # Download
    Invoke-WebRequest -Uri $url -OutFile $zipPath
    
    Write-Host "Extracting PHP to $phpDir..."
    Expand-Archive -Path $zipPath -DestinationPath $phpDir -Force
    
    Write-Host "Cleaning up ZIP file..."
    Remove-Item $zipPath -Force
    
    Write-Host "PHP extraction complete."
} else {
    Write-Host "PHP is already installed in $phpDir"
}

# Ensure php.ini exists and is configured
$iniPath = Join-Path $phpDir "php.ini"
if (!(Test-Path $iniPath)) {
    Write-Host "Creating default php.ini with required Laravel extensions..."
    $iniContent = @"
[PHP]
extension_dir = "ext"
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_sqlite
extension=sqlite3
variables_order = "EGPCS"
max_execution_time = 300
memory_limit = 512M
post_max_size = 50M
upload_max_filesize = 50M
date.timezone = UTC
"@
    Set-Content -Path $iniPath -Value $iniContent
    Write-Host "php.ini created successfully."
} else {
    Write-Host "php.ini already exists."
}
