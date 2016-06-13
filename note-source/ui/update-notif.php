<?php
  // Code that checks for updates, and notifies the user if there is an update.
error_reporting(-1);
ini_set('display_errors', 'On');
$metadata = json_decode(file_get_contents('http://bramkorsten.io/downloads/note/metadata.json'), true);
$newversion = $metadata['core']['version'];
$currentmetadata = json_decode(file_get_contents('config/config.json'), true);
$oldversion = $currentmetadata['core']['version'];
if (version_compare($oldversion, $newversion) == -1) {
    echo '<div class="message">
            <p>
              An update is available. <a href="dependencies/update.php">Update</a>
            </p>
            <div class="arrow-down"></div>
          </div>';
}
?>