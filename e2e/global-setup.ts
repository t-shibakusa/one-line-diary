import { execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

export default async function globalSetup(): Promise<void> {
    const databasePath = path.resolve('database/e2e.sqlite');

    if (fs.existsSync(databasePath)) {
        fs.rmSync(databasePath);
    }

    fs.closeSync(fs.openSync(databasePath, 'w'));

    const env = {
        ...process.env,
        DB_CONNECTION: 'sqlite',
        DB_DATABASE: databasePath,
        SESSION_DRIVER: 'file',
        CACHE_STORE: 'file',
        QUEUE_CONNECTION: 'sync',
    };

    execSync('php artisan migrate:fresh --seed --seeder=E2ESeeder --force', {
        stdio: 'inherit',
        env,
    });
}
