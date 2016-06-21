<?php
  // Code that checks for updates, and notifies the user if there is an update.
error_reporting(0);
ini_set('display_errors', 'Off');
ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
$metadata = json_decode(file_get_contents('https://api.github.com/repositories/60926745/releases/latest'), true);
$newversion = $metadata['tag_name'];
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