<?php

function backup($name, $user, $pass = NULL) {
  $filename='../backup/note_db_backup_'.date('G_a_m_d_y').'.sql';
  if ($pass == NULL) {
    $result=exec('mysqldump notecms --single-transaction --user='. $user .' -r '. $filename . ' 2>&1', $output, $return_var);
  }
  else {
    $result=exec('mysqldump notecms --single-transaction --user='. $user .' -p='. $pass .' -r '. $filename . ' 2>&1', $output, $return_var);
  }
  if($return_var == 0){
  return 0;
  }
  else {
    global $backup_output;
    $backup_output = $output;
    return 1;
  }
}
