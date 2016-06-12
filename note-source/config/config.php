<?php
// this should be a new version
// php file to get all the options from a JSON file.
// This file will parse all the nessesary options, and set them to variables.
$json_options = file_get_contents(dirname(__FILE__). "/config.json");
$options = json_decode($json_options);
// Database connection details.
$s_Server = $options->database->server;
$s_Username = $options->database->username;
$s_Password = $options->database->password;
$s_Database = $options->database->data;
$s_CoreVersion = $options->core->version;
?>