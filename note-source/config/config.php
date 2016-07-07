<?php
include(dirname(__FILE__) . "/../dependencies/password.php");
$json_options = file_get_contents(dirname(__FILE__) . "/config.json");
$options = json_decode($json_options);
// Database connection details.
$s_Server = $options->database->server;
$s_Username = $options->database->username;
$s_Password = $options->database->password;
$s_Database = $options->database->data;
$s_CoreVersion = $options->core->version;
?>