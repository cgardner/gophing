#! /usr/bin/env php
<?php

ini_set('display_errors', 'Off');

if (count($argv) < 2 || !isset($argv[1])) {
   exit (1);
}

$path = getcwd();
$args = explode(DIRECTORY_SEPARATOR, dirname($argv[1]));
// Go through all of the directories until one is reached with a phing build file.
while (count($args) > 0) {
    // If there is a build file, run phing. Otherwise, just continue
    $searchFile = implode(DIRECTORY_SEPARATOR, array_merge(array($path), $args, array('build.xml')));
    if (file_exists($searchFile)) {
        chdir(dirname($searchFile));
        require_once 'phing/Phing.php';

        // GO PHING!
        try {
            // Set up the Phing environment
            Phing::startup();
    
            // Invoke the entry point
            Phing::fire(array('vim'));

            // Invoke the shutdown routines
            Phing::shutdown();
        }
        catch (ConfigurationException $e) {
            Phing::printMessage($e);
            exit(-1);
        }
        catch (Exception $e) {
            exit(1);
        }

        break;
    }
    array_pop($args);
}
chdir($path);
