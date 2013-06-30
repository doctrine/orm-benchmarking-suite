<?php

if (!isset($argv[1])) {
    echo "No path given!\n";
    exit(1);
}

$currentPath = realpath(dirname($argv[0]));
$path = $argv[1];

chdir($path);
$log = shell_exec("git tag -l");
$lines = explode("\n", $log);
$version = 0;

foreach ($lines as $line) {
    if (empty($line)) {
        continue;
    }

    @list($hash, $message) = explode(" ", $line, 2);

    ob_start();
    chdir($path);
    exec("git checkout $hash > /dev/null 2>&1 && git submodule update --init -f > /dev/null 2>&1 && composer install --prefer-dist");
    chdir($currentPath);
    ob_end_clean();

    $output = array();
    $return = 0;
    $result = exec('php ' . __DIR__ . '/run.php ' . escapeshellarg($path), $output, $return );

    if ($return !== 0) {
        continue;
    }

    $result = json_decode($result, true);

    if ($version === 0) {
        $header = 'Version;' . implode(';', array_keys($result));
        echo $header . "\n";
    }
    $version++;

    echo $hash . ';' . implode(';', array_map(function ($result) {
        return sprintf('%4.10f', $result['duration']);
    }, $result)) . "\n";
}

