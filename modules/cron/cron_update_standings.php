<?php
	include_once 	"properties.php";

	log_entry(0, "Beginning Cron Job: Update Standings...");

    include_once    "cron_backup_database.php";
	include_once 	"cron_update_encounter_ranks.php";
	include_once 	"cron_update_all_point_ranks.php";

	log_entry(0, "Cron Job: Update Standings Completed!");
?>