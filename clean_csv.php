<?php
/**
 * CSV Cleaner - removes bad rows before import
 * Usage: php clean_csv.php input.csv output.csv
 */

$input  = $argv[1] ?? null;
$output = $argv[2] ?? null;

if (! $input || ! $output) {
    echo "Usage: php clean_csv.php input.csv output.csv\n";
    exit(1);
}

if (! file_exists($input)) {
    echo "File not found: $input\n";
    exit(1);
}

// Read raw bytes, detect encoding, convert to UTF-8
$content = file_get_contents($input);
if (! mb_check_encoding($content, 'UTF-8')) {
    $content = mb_convert_encoding($content, 'UTF-8', 'Windows-1252');
}
// Strip UTF-8 BOM
if (str_starts_with($content, "\xEF\xBB\xBF")) {
    $content = substr($content, 3);
}

// Write to temp file so fgetcsv can read it
$tmp = tempnam(sys_get_temp_dir(), 'csv_');
file_put_contents($tmp, $content);

$in  = fopen($tmp, 'r');
$out = fopen($output, 'w');

$header     = null;
$written    = 0;
$skipped    = 0;
$seen       = [];   // dedup by phone digits

while (($row = fgetcsv($in, escape: '\\')) !== false) {
    // First row = header
    if ($header === null) {
        $header = $row;
        fputcsv($out, $header);
        continue;
    }

    $name  = trim((string) ($row[0] ?? ''));
    $email = trim((string) ($row[1] ?? ''));
    $phone = trim((string) ($row[2] ?? ''));
    $city  = trim((string) ($row[3] ?? ''));

    // Treat pandas NaN exports as empty
    foreach ([&$name, &$email, &$phone, &$city] as &$v) {
        if (strtolower($v) === 'nan') $v = '';
    }
    unset($v);

    // Skip empty or garbage-only names (?, ., spaces)
    if ($name === '' || preg_match('/^[\?\.\s]+$/u', $name)) {
        $skipped++;
        continue;
    }

    // Skip obvious mojibake names (e.g. SÃ Ã§ha, ChÃ¤£tÃ)
    if (preg_match('/Ã[^\s]/u', $name) || str_contains($name, 'Ã¬') || str_contains($name, 'Ã§')) {
        $skipped++;
        continue;
    }

    // Normalize phone to digits only for dedup check
    $digits = preg_replace('/\D/', '', $phone);

    // Skip duplicate phone numbers
    if ($digits !== '' && isset($seen[$digits])) {
        $skipped++;
        continue;
    }
    if ($digits !== '') {
        $seen[$digits] = true;
    }

    // Ensure all fields are clean UTF-8
    $name  = mb_convert_encoding($name,  'UTF-8', 'UTF-8');
    $email = mb_convert_encoding($email, 'UTF-8', 'UTF-8');
    $phone = mb_convert_encoding($phone, 'UTF-8', 'UTF-8');
    $city  = mb_convert_encoding($city,  'UTF-8', 'UTF-8');

    fputcsv($out, [$name, $email, $phone, $city]);
    $written++;
}

fclose($in);
fclose($out);
unlink($tmp);

echo "Done!\n";
echo "  Written : $written contacts\n";
echo "  Skipped : $skipped rows (empty names, garbage, mojibake, duplicates)\n";
echo "  Output  : $output\n";
