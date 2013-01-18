<?php

require dirname(__DIR__) . '/lib/Fiber.php';

$fiber = new Fiber();

$fiber->db = $fiber->share(function () {
    return microtime(true);
});

$fiber->dbm = $fiber->protect($fiber->share(function () {
    return rand();
}));

$fiber->extend('db', $fiber->share(function ($db) {
    $db = rand(0, 1000);
    return $db;
}));

$d1 = $fiber->db;
$d2 = $fiber->db;

var_dump($d1, $d2);
var_dump($fiber->db, $fiber->dbm(), $fiber->dbm());