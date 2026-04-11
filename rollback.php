<?php

require_once __DIR__ . '/db/config/config.php';

echo "Rolling back...\n";

// get latest batch
$result = mysqli_query($con, "SELECT MAX(batch) as batch FROM migrations");
$row = mysqli_fetch_assoc($result);
$batch = $row['batch'];

$files = mysqli_query($con, "SELECT * FROM migrations WHERE batch = $batch");

while ($file = mysqli_fetch_assoc($files)) {

    $migrationFile = $file['migration'];

    echo "Rolling back: $migrationFile\n";

    $migration = require __DIR__ . "/database/migrations/$migrationFile";

    $migration['down']($con);

    mysqli_query($con, "DELETE FROM migrations WHERE migration = '$migrationFile'");
}

echo "Rollback complete!\n";