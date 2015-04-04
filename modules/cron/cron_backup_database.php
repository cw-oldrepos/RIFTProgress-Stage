<?php
    include_once    "properties.php";

    log_entry(0, "Starting backup process...");

    $year 	= date('Y');
    $month 	= date('n')."-".date('M');
    $day 	= date('d');

    $backup_file    = $dbname.date("Y-m-d_H-i").".sql";
    $command        = "mysqldump --no-defaults -h$dbhost -u$dbuser -p$dbpass $dbname > $backup_file"; // > stdout_output.txt 2>stderr_output.txt
    exec($command);

    $old_backup_path 	= strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_cron']}{$backup_file}");
    $backup_path  		= strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_backups']}{$year}/{$month}/{$day}");

    if ( !file_exists($backup_path) ) {
    	if ( !mkdir($backup_path, 0777, true) ) {
    		log_entry(2, "Folder unable to be created: {$backup_path}");
    	}
    }

    $new_backup_path = strtolower("{$backup_path}/{$backup_file}");

    rename($old_backup_path, $new_backup_path);
    log_entry(0, "Backup process Completed!");
?>
