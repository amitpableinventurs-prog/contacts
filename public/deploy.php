<?php

$secret = getenv('DEPLOY_SECRET') ?: 'laracontact-deploy-2024';

// Verify GitHub signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload   = file_get_contents('php://input');
$expected  = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    die('Forbidden');
}

$data = json_decode($payload, true);

// Only deploy on push to main branch
if (($data['ref'] ?? '') !== 'refs/heads/main') {
    die('Not main branch, skipping.');
}

$projectPath = realpath(__DIR__ . '/..');
$logFile     = $projectPath . '/storage/logs/deploy.log';

$commands = [
    "cd {$projectPath} && git pull origin main 2>&1",
    "cd {$projectPath} && composer install --no-dev --optimize-autoloader 2>&1",
    "cd {$projectPath} && php artisan migrate --force 2>&1",
    "cd {$projectPath} && php artisan config:cache 2>&1",
    "cd {$projectPath} && php artisan route:cache 2>&1",
    "cd {$projectPath} && php artisan view:clear 2>&1",
];

$output = date('[Y-m-d H:i:s]') . " Deploy started\n";

foreach ($commands as $cmd) {
    $result  = shell_exec($cmd);
    $output .= "$ {$cmd}\n{$result}\n";
}

$output .= date('[Y-m-d H:i:s]') . " Deploy finished\n\n";

file_put_contents($logFile, $output, FILE_APPEND);

http_response_code(200);
echo 'Deployed successfully.';
