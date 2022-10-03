<?php

/*** Truncate Database Table ***/
global $wpdb, $table_prefix;
$table_name = $table_prefix . 'employee';

$truncate_table_query = "TRUNCATE TABLE $table_name;";
$wpdb->query($truncate_table_query);
