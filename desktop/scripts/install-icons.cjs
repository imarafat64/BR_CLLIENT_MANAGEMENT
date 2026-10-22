const fs = require('fs');
const path = require('path');
const pngToIco = require('./png2ico.cjs');

const rootDir = path.join(__dirname, '..', '..');
const publicImagesDir = path.join(rootDir, 'public', 'images');
const resourcesAppDir = path.join(rootDir, 'resources', 'app');

// Absolute paths to generated PNG assets in brain folder (historical fallback)
const brainDir = 'C:\\Users\\User\\.gemini\\antigravity-ide\\brain\\569388a9-f7e0-4683-bd6f-c23227ade35f';
const centerpiecePng = path.join(brainDir, 'app_logo_centerpiece_1782411505281.png');
const createPng = path.join(brainDir, 'icon_create_update_1782411518084.png');
const readPng = path.join(brainDir, 'icon_read_search_1782411536586.png');
const editPng = path.join(brainDir, 'icon_edit_1782411549263.png');
const deletePng = path.join(brainDir, 'icon_delete_1782411561395.png');

function ensureDir(dir) {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
        console.log(`Created directory: ${dir}`);
    }
}

function copyFile(src, dest) {
    if (fs.existsSync(src)) {
        fs.copyFileSync(src, dest);
        console.log(`Copied ${path.basename(src)} to ${dest}`);
    } else {
        console.log(`Source file ${src} not found, skipping copy.`);
    }
}

async function run() {
    ensureDir(publicImagesDir);
    ensureDir(resourcesAppDir);

    // Determine the source centerpiece image
    let sourceCenterpiece = path.join(publicImagesDir, 'app_logo.png');
    if (!fs.existsSync(sourceCenterpiece)) {
        sourceCenterpiece = centerpiecePng;
    }

    if (!fs.existsSync(sourceCenterpiece)) {
        console.error('Error: Source app logo centerpiece image not found!');
        process.exit(1);
    }

    // 1. Convert centerpiece to ICO files
    const appIcoPath = path.join(resourcesAppDir, 'app.ico');
    const faviconIcoPath = path.join(rootDir, 'public', 'favicon.ico');
    
    await pngToIco(sourceCenterpiece, appIcoPath);
    await pngToIco(sourceCenterpiece, faviconIcoPath);

    // 2. Copy other PNGs to public/images if brainDir files are available
    if (fs.existsSync(brainDir)) {
        copyFile(sourceCenterpiece, path.join(publicImagesDir, 'app_logo.png'));
        copyFile(createPng, path.join(publicImagesDir, 'create_update.png'));
        copyFile(readPng, path.join(publicImagesDir, 'read_search.png'));
        copyFile(editPng, path.join(publicImagesDir, 'edit.png'));
        copyFile(deletePng, path.join(publicImagesDir, 'delete.png'));
    }

    console.log('All icons successfully processed and copied!');
}

run().catch(err => {
    console.error('Error during icon installation:', err);
    process.exit(1);
});
