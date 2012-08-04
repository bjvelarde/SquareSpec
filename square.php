<?php
require('traits.php');

define('SPECS_DIR', '../specs/');

use SquareSpec\SpecSubject as SpecSubject;

$t1 = microtime(TRUE);

$files = scandir(SPECS_DIR);
echo "\n";
foreach ($files as $file) {
    if (preg_match('/^([a-z0-9_]+)\.specs\.php$/', $file, $matches)) {
        include(SPECS_DIR . $matches[0]);
    } 
}
echo "\n";
if (SpecSubject::$failures) {    
    echo "Failed on: \n -" . implode("\n -", SpecSubject::$failures);
    echo "\n";
}
echo "\nFailures: " . count(SpecSubject::$failures) . "\n";
echo 'Success: ' . SpecSubject::$success . "\n";
echo 'Total: ' . SpecSubject::$total . "\n\n";

echo 'Time Elapsed: ' . (microtime(TRUE) - $t1);
?>