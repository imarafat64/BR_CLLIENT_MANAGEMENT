const fs = require('fs');
const pngToIco = require('png-to-ico').default;

async function convert(pngPath, icoPath) {
    try {
        const buf = await pngToIco(pngPath);
        fs.writeFileSync(icoPath, buf);
        console.log(`Successfully converted ${pngPath} to ICO format at ${icoPath}`);
    } catch (err) {
        console.error(`Error converting ${pngPath} to ICO:`, err);
        throw err;
    }
}

// Check arguments if run from CLI
if (require.main === module) {
    const args = process.argv.slice(2);
    if (args.length < 2) {
        console.error('Usage: node png2ico.cjs <input.png> <output.ico>');
        process.exit(1);
    }
    convert(args[0], args[1]).catch(err => {
        console.error(err);
        process.exit(1);
    });
}

module.exports = convert;
