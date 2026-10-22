const { app, BrowserWindow } = require('electron');
const path = require('path');
const fs = require('fs');
const { spawn, execSync } = require('child_process');
const net = require('net');

let phpProcess = null;
let serverPort = null;

// Determine AppData directory for storing SQLite and storage files
const appDataRoot = path.join(app.getPath('appData'), 'MY CRUD');
const storagePath = path.join(appDataRoot, 'storage');
const dbDir = path.join(appDataRoot, 'database');
const dbPath = path.join(dbDir, 'database.sqlite');

// Ensure necessary directories exist
function ensureDirectories() {
    const dirs = [
        appDataRoot,
        dbDir,
        storagePath,
        path.join(storagePath, 'app'),
        path.join(storagePath, 'app', 'public'),
        path.join(storagePath, 'framework'),
        path.join(storagePath, 'framework', 'cache'),
        path.join(storagePath, 'framework', 'cache', 'data'),
        path.join(storagePath, 'framework', 'sessions'),
        path.join(storagePath, 'framework', 'views'),
        path.join(storagePath, 'logs')
    ];

    dirs.forEach(dir => {
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
        }
    });

    // Create database file if it doesn't exist
    if (!fs.existsSync(dbPath)) {
        const templateDbPath = path.join(__dirname, '..', 'database', 'database.sqlite');
        if (fs.existsSync(templateDbPath)) {
            fs.copyFileSync(templateDbPath, dbPath);
            console.log('Copied template SQLite database to AppData.');
        } else {
            fs.writeFileSync(dbPath, '');
            console.log('Created empty SQLite database in AppData.');
        }
    }
}

// Find a free port dynamically
function findFreePort(callback) {
    const server = net.createServer();
    server.listen(0, '127.0.0.1', () => {
        const port = server.address().port;
        server.close(() => {
            callback(port);
        });
    });
}

// Setup common environment variables for PHP processes
function getEnvVariables() {
    return {
        ...process.env,
        APP_ENV: 'production',
        APP_DEBUG: 'false',
        APP_KEY: 'base64:lITGLfcoobzvJY98Fd2JOE3spSM2ykt5sYsC6UjN1O8=', // Fallback encryption key
        DB_CONNECTION: 'sqlite',
        DB_DATABASE: dbPath,
        LARAVEL_STORAGE_PATH: storagePath,
        PHP_CLI_SERVER_WORKERS: '4', // Allow concurrent handling of requests
        SESSION_DRIVER: 'file',
        CACHE_STORE: 'file'
    };
}

// Start local PHP server
function startPhpServer(port, callback) {
    const phpExe = path.join(__dirname, 'php', 'php.exe');
    const artisanPath = path.join(__dirname, '..', 'artisan');
    const publicPath = path.join(__dirname, '..', 'public');

    console.log(`Starting PHP server on port ${port}...`);
    
    // Spawn PHP server process
    phpProcess = spawn(
        phpExe,
        ['-S', `127.0.0.1:${port}`, '-t', publicPath],
        {
            cwd: path.join(__dirname, '..'),
            env: getEnvVariables(),
            detached: false // Keep it bound to this node process
        }
    );

    phpProcess.stdout.on('data', (data) => console.log(`PHP stdout: ${data}`));
    phpProcess.stderr.on('data', (data) => console.error(`PHP stderr: ${data}`));

    // Poll to check when the server is ready
    const maxAttempts = 30;
    let attempts = 0;
    
    const interval = setInterval(() => {
        attempts++;
        const socket = new net.Socket();
        
        socket.connect(port, '127.0.0.1', () => {
            socket.destroy();
            clearInterval(interval);
            console.log(`PHP server is online on port ${port}`);
            callback(true);
        });

        socket.on('error', () => {
            socket.destroy();
            if (attempts >= maxAttempts) {
                clearInterval(interval);
                console.error('Failed to start PHP server in time.');
                callback(false);
            }
        });
    }, 100);
}

// Run Laravel migrations
function runMigrations() {
    const phpExe = path.join(__dirname, 'php', 'php.exe');
    const artisanPath = path.join(__dirname, '..', 'artisan');

    console.log('Running Laravel migrations...');
    try {
        const output = execSync(`"${phpExe}" "${artisanPath}" migrate --force`, {
            env: getEnvVariables(),
            encoding: 'utf8'
        });
        console.log('Migrations output:', output);
    } catch (error) {
        console.error('Migration failed:', error.message);
        if (error.stdout) console.log('Migration stdout:', error.stdout);
        if (error.stderr) console.error('Migration stderr:', error.stderr);
    }
}

function createWindow(port) {
    const win = new BrowserWindow({
        width: 1280,
        height: 800,
        title: 'MY CRUD',
        autoHideMenuBar: true,
        icon: path.join(__dirname, '..', 'resources', 'app', 'app.ico'),
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true
        }
    });

    win.loadURL(`http://127.0.0.1:${port}`);

    win.on('closed', () => {
        if (phpProcess) {
            phpProcess.kill();
            phpProcess = null;
        }
    });
}

app.whenReady().then(() => {
    ensureDirectories();
    
    findFreePort((port) => {
        serverPort = port;
        startPhpServer(port, (success) => {
            if (success) {
                // Run migrations before loading the screen
                runMigrations();
                createWindow(port);
            } else {
                app.quit();
            }
        });
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('will-quit', () => {
    if (phpProcess) {
        phpProcess.kill();
        phpProcess = null;
    }
});
