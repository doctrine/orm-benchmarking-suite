<?php

echo str_repeat(" ", 101) . "| Insert | findPk | complex| hydrate|  with  | update |\n";
echo str_repeat(" ", 101) . "|--------|--------|--------|--------|--------|--------|\n";

chdir("/home/benny/code/php/wsnetbeans/doctrine2");
#$log = shell_exec("git log --oneline --all");
$log = shell_exec("git tag -l");
$lines = explode("\n", $log);
foreach ($lines as $line) {
    list($hash, $message) = explode(" ", $line, 2);
    ob_start();
    shell_exec("git checkout $hash && git submodule update");
    ob_end_clean();
    passthru('php ' . __DIR__ . '/doctrine2/TestRunner.php ' . escapeshellarg($hash . ' ' . substr($message, 0, 80)) );
    echo "\n";
}

