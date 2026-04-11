<?php

require_once __DIR__ . '/db/config/config.php';

echo "Running migrations...\n";

// get current batch
$result = mysqli_query($con, "SELECT MAX(batch) as batch FROM migrations");
$row = mysqli_fetch_assoc($result);
$batch = $row['batch'] + 1;

$files = scandir(__DIR__ . '/database/migrations');

foreach ($files as $file) {

    if ($file === '.' || $file === '..') continue;

    // check if already migrated
    $check = mysqli_query($con, "SELECT * FROM migrations WHERE migration = '$file'");
    
    if (mysqli_num_rows($check) > 0) {
        echo "Skipped: $file\n";
        continue;
    }

    echo "Migrating: $file...\n";

    $migration = require __DIR__ . "/database/migrations/$file";

    $migration['up']($con);

    mysqli_query($con, "INSERT INTO migrations (migration, batch) VALUES ('$file', $batch)");

    echo "Done: $file\n";
}

echo "All done!\n";