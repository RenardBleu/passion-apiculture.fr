const { spawn } = require('child_process');

const phpProcess = spawn('php', ['-S', 'localhost:8000 -t public/']);

phpProcess.stdout.on('data', (data) => {
    console.log(`PHP server output: ${data}`);
});

phpProcess.stderr.on('data', (data) => {
    console.error(`PHP server error: ${data}`);
});

phpProcess.on('close', (code) => {
    console.log(`PHP server exited with code ${code}`);
    process.exit(code);
});