<?php

echo str_repeat(" ", 101) . "| Insert | findPk | complex| hydrate|  with  | update |\n";
echo str_repeat(" ", 101) . "|--------|--------|--------|--------|--------|--------|\n";

passthru('php ' . __DIR__ . '/doctrine2/TestRunner.php');
