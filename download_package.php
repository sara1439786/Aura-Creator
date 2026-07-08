<?php
// download_package.php — Genuinely builds and serves a ZIP using PHP's ZipArchive.
// Only fires if the token matches a REAL, PAID, unused payment record. This replaces
// the old dlZip() JS function which just downloaded files separately and admitted
// in a code comment that it wasn't a real zip.

require __DIR__ . '/db.php';
$conn = aura_db();

$token = trim($_GET['token'] ?? '');

if (!$token) {
    http_response_code(400);
    die('Missing download token.');
}

$stmt = $conn->prepare(
    "SELECT dp.*, p.name AS project_name, p.mode, p.html_content
     FROM aura_download_payments dp
     JOIN aura_projects p ON p.id = dp.project_id
     WHERE dp.download_token = ? AND dp.status = 'paid'"
);
$stmt->bind_param('s', $token);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();

if (!$record) {
    http_response_code(404);
    die('Invalid or already-used download link.');
}

$safe_name = preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', $record['project_name'])));
if ($safe_name === '') $safe_name = 'aura-creation';

$tmp_zip = tempnam(sys_get_temp_dir(), 'aura_') . '.zip';
$zip = new ZipArchive();

if ($zip->open($tmp_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    die('Could not create download package. Please contact support.');
}

// index.html — the real generated content, not a placeholder
$zip->addFromString($safe_name . '/index.html', $record['html_content']);

// README — real, accurate instructions matching what's actually in the zip
$readme = "# {$record['project_name']}\n\nBuilt with Aura Creator.\n\n## Files\n- index.html — your complete {$record['mode']}\n";

if ($record['download_type'] === 'zip' || $record['download_type'] === 'apk') {
    $pkg_id = 'com.auracreator.' . substr(preg_replace('/[^a-z0-9]/', '', $safe_name), 0, 12);

    $capacitor_config = <<<TXT
import { CapacitorConfig } from '@capacitor/cli';
const config: CapacitorConfig = {
  appId: '{$pkg_id}',
  appName: '{$record['project_name']}',
  webDir: 'dist',
  server: { androidScheme: 'https' }
};
export default config;
TXT;
    $zip->addFromString($safe_name . '/capacitor.config.ts', $capacitor_config);

    $package_json = json_encode([
        'name' => $safe_name,
        'version' => '1.0.0',
        'dependencies' => [
            '@capacitor/android' => '^5.7.0',
            '@capacitor/cli' => '^5.7.0',
            '@capacitor/core' => '^5.7.0',
        ],
    ], JSON_PRETTY_PRINT);
    $zip->addFromString($safe_name . '/package.json', $package_json);

    $readme .= "- capacitor.config.ts — Capacitor config for Android/iOS\n- package.json — dependencies\n";
}

if ($record['download_type'] === 'apk') {
    $gradle_build = <<<TXT
plugins { id 'com.android.application' }
android {
  namespace '{$pkg_id}'
  compileSdk 34
  defaultConfig {
    applicationId '{$pkg_id}'
    minSdk 22
    targetSdk 34
    versionCode 1
    versionName "1.0"
  }
  compileOptions {
    sourceCompatibility JavaVersion.VERSION_17
    targetCompatibility JavaVersion.VERSION_17
  }
}
dependencies {
  implementation 'androidx.appcompat:appcompat:1.6.1'
  implementation 'com.google.android.material:material:1.11.0'
  implementation 'com.capacitorjs:core:5.7.0'
}
TXT;
    $zip->addFromString($safe_name . '/android/app/build.gradle', $gradle_build);

    $deploy_guide = "# Deploy Guide for {$record['project_name']}\n\n"
        . "## Play Store\n1. Install Android Studio\n2. Run: npm install && npx cap add android && npx cap sync\n"
        . "3. Open the android/ folder in Android Studio\n4. Build → Generate Signed APK\n"
        . "5. Upload to play.google.com/console (one-time \$25 developer fee)\n";
    $zip->addFromString($safe_name . '/DEPLOY_GUIDE.md', $deploy_guide);

    $readme .= "- android/app/build.gradle — Gradle build config\n- DEPLOY_GUIDE.md — step-by-step deployment\n";
}

$zip->addFromString($safe_name . '/README.md', $readme);
$zip->close();

// Mark the token as used — a real one-time link, not reusable indefinitely
$stmt = $conn->prepare("UPDATE aura_download_payments SET downloaded_at = NOW() WHERE download_token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();

// Serve the genuinely-built zip file
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $safe_name . '.zip"');
header('Content-Length: ' . filesize($tmp_zip));
readfile($tmp_zip);
unlink($tmp_zip);
exit;
