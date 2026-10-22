$version = "2.6.0"
$zipName = "winCodeSign-$version.7z"
$url = "https://github.com/electron-userland/electron-builder-binaries/releases/download/winCodeSign-$version/winCodeSign-$version.7z"
$cacheRoot = "$env:LOCALAPPDATA\electron-builder\Cache\winCodeSign"
$targetDir = Join-Path $cacheRoot $version
$zipPath = Join-Path $cacheRoot $zipName
$exe7z = Join-Path $PSScriptRoot "..\..\node_modules\7zip-bin\win\x64\7za.exe"

if (!(Test-Path $cacheRoot)) {
    New-Item -ItemType Directory -Force -Path $cacheRoot | Out-Null
}

if (Test-Path $targetDir) {
    Write-Host "winCodeSign $version already cached at $targetDir"
    exit 0
}

Write-Host "Downloading winCodeSign from $url..."
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12 -bor [Net.SecurityProtocolType]::Tls13
Invoke-WebRequest -Uri $url -OutFile $zipPath

Write-Host "Extracting winCodeSign (excluding darwin folder to prevent symlink errors)..."
& $exe7z x -bd $zipPath "-o$targetDir" "-x!darwin"

Write-Host "Cleaning up 7z file..."
Remove-Item $zipPath -Force

Write-Host "winCodeSign setup complete!"
