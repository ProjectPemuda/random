<?php

$ulang = 2;

$fulang = $_POST['ulang'];

$list = explode("\n", "./list.txt");

$isi_text = "";

$fname2 = rand(1000, 10000);

$name2 = $_POST['file'] . "_";

$namef = $_POST['file'] . "_" . $fname2 ;

mkdir($namef, 0777, true);

for ($l = 0; $l < $fulang; $l++) {
    for ($i = 0; $i < $ulang; $i++) {
        $isi_text = $isi_text . $list[array_rand($list)] . "\n\n";
    }

    $fname = rand(1000, 10000);

    $fp = fopen($namef . "/" . $name2 . $fname . ".txt", "w");
    fwrite($fp, $isi_text);
    fclose($fp);

    echo $isi_text;
}

$rootPath = realpath($namef);

// Initialize archive object
$zip = new ZipArchive();
$zip->open($namef . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

header("Location: ./" . $namef . ".zip");