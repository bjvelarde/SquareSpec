<?php
require('traits.php');

define('SPECS_DIR', '../specs/');

use SquareSpec\SpecSubject as SpecSubject;

$t1 = microtime(TRUE);

echo "\n";
$ok = TRUE;
if (!isset($argv[1])) {
    $files = scandir(SPECS_DIR);
    foreach ($files as $file) {
        if (preg_match('/^([a-z0-9_]+)\.specs\.php$/', $file, $matches)) {
            include(SPECS_DIR . $matches[0]);
        } 
    }
} elseif (file_exists(SPECS_DIR . $argv[1] . '.specs.php')) {
    include(SPECS_DIR . $argv[1] . '.specs.php');
} else {
    $ok = FALSE;
}
if ($ok) {
    echo "\n";
    if (SpecSubject::$failures) {    
        echo "Failed on: \n -" . implode("\n -", SpecSubject::$failures);
        echo "\n";
    }
    echo "\nFailures: " . count(SpecSubject::$failures) . "\n";
    echo 'Success: ' . SpecSubject::$success . "\n";
    echo 'Total: ' . SpecSubject::$total . "\n\n";
    
    echo 'Time Elapsed: ' . (microtime(TRUE) - $t1);
} else {
    echo "Can't find specs for $argv[1]";
}
?>