<?php

global $wpdb;

$charset    = $wpdb->get_charset_collate();
$table      = $wpdb->prefix."mailers";

$wpdb->query("DROP TABLE $table");