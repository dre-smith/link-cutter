<?php
include "includes/config.php";

function get_url($page = '') {
    return HOST . "/$page";
}

function db() {
    try {
        return $db = new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PROException $e) {
        die($e->getMessage());
    }
}

function db_query($sql = '', $exec = false) {
    if (empty($sql)) return false;

    if($exec) {
        return db()->exec($sql);
    }
	return db()->query($sql);
}

function get_users_count() {
    return db_query("SELECT COUNT(id) FROM `users`;")->fetchColumn();
}

function get_links_count() {
    return db_query("SELECT COUNT(id) FROM `links`;")->fetchColumn();
}

function get_views_count() {
    return db_query("SELECT SUM(`views`) FROM `links`;")->fetchColumn();
}

function get_link_info($url) {
    if (empty($url)) return [];
    return db_query("SELECT * FROM `links` WHERE `short_link` = '$url';")->fetch();
}

function update_views($url) {
    if (empty($url)) return false;
    return db_query("UPDATE `links` SET `views` = `views` + 1 WHERE `short_link` = '$url';", true);
}
