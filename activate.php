<?php

## SQL
global $wpdb;

$charset    = $wpdb->get_charset_collate();
$table      = $wpdb->prefix."mailers";

$wpdb->query("CREATE TABLE IF NOT EXISTS $table (
    `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` varchar(20) NOT NULL DEFAULT 'mail',
    `state` varchar(20) NOT NULL DEFAULT 'pendiente',
    `handler` timestamp NOT NULL,
    `ip` varchar(15) NOT NULL,
    `agent` text NOT NULL,
    `meta` text NOT NULL,
    `created` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated` timestamp NOT NULL,
    PRIMARY KEY(ID)) $charset; ");

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');


