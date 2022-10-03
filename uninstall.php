<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

global $wpdb, $table_prefix;
$table_name = $table_prefix . 'employee';

$drop_table_query = "DROP TABLE $table_name";
$wpdb->query($drop_table_query);
