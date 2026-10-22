const fs = require('fs');
const path = require('path');
const { spawnSync } = require('child_process');

async function prep() {
    const version = "2.6.0";
    const zipName = `winCodeSign-${version}.7z`;
    const url = `https://github.com/electron-userland/electron-builder-binaries/releases/download/winCodeSign-${version}/${zipName}`;

    const cacheRoot = path.join(process.env.LOCALAPPDATA, 'electron-builder', 'Cache', 'winCodeSign');
    const targetDir1 = path.join(cacheRoot, version);
    const targetDir2 = path.join(cacheRoot, 'winCodeSign-' + version);
    const zipPath = path.join(cacheRoot, zipName);
    const exe7z = path.join(__dirname, '..', '..', 'node_modules', '7zip-bin', 'win', 'x64', '7za.exe');

    console.log(`Cache root: ${cacheRoot}`);
    console.log(`Target directory 1: ${targetDir1}`);
    console.log(`Target directory 2: ${targetDir2}`);
    console.log(`7-Zip path: ${exe7z}`);

    if (!fs.existsSync(cacheRoot)) {
        fs.mkdirSync(cacheRoot, { recursive: true });
    }

    if (fs.existsSync(targetDir1) && fs.existsSync(targetDir2)) {
        console.log(`winCodeSign ${version} already cached at both locations.`);
        process.exit(0);
    }

    console.log(`Downloading winCodeSign from ${url}...`);
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Failed to download: ${response.statusText}`);
        }
        const arrayBuffer = await response.arrayBuffer();
        fs.writeFileSync(zipPath, Buffer.from(arrayBuffer));
        console.log('Download complete.');
    } catch (err) {
        console.error('Error downloading winCodeSign:', err.message);
        process.exit(1);
    }

    console.log('Extracting winCodeSign to Target 1 (excluding darwin/macOS symlinks)...');
    try {
        const result = spawnSync(exe7z, [
            'x', '-bd', zipPath, `-o${targetDir1}`, '-x!darwin'
        ], { stdio: 'inherit' });

        if (result.status !== 0) {
            console.error('Extraction to Target 1 failed with exit code:', result.status);
            process.exit(1);
        }
    } catch (err) {
        console.error('Error running 7z extraction for Target 1:', err.message);
        process.exit(1);
    }

    console.log('Extracting winCodeSign to Target 2 (excluding darwin/macOS symlinks)...');
    try {
        const result = spawnSync(exe7z, [
            'x', '-bd', zipPath, `-o${targetDir2}`, '-x!darwin'
        ], { stdio: 'inherit' });

        if (result.status !== 0) {
            console.error('Extraction to Target 2 failed with exit code:', result.status);
            process.exit(1);
        }
        console.log('Extraction complete.');
    } catch (err) {
        console.error('Error running 7z extraction for Target 2:', err.message);
        process.exit(1);
    }

    console.log('Cleaning up 7z archive...');
    try {
        fs.unlinkSync(zipPath);
        console.log('Cleanup complete.');
    } catch (err) {
        console.warn('Warning: Could not delete temp 7z file:', err.message);
    }

    console.log('winCodeSign cache preparation completed successfully!');
}

prep().catch(err => {
    console.error('Unhandled error:', err);
    process.exit(1);
});
