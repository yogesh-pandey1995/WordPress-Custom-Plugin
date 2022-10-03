<?php

global $wpdb, $table_prefix;
$table_name = $table_prefix . 'employee';

$sql = "CREATE TABLE IF NOT EXISTS `$table_name` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `email` VARCHAR(55) NOT NULL , `m_number` INT(10) NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;";

$wpdb->query($sql);
