<?php
$GLOBALS['db_path'] = __DIR__ . '/../database/placement.db';

function getDB() {
    if (!isset($GLOBALS['_pdo'])) {
        $path = $GLOBALS['db_path'];
        $GLOBALS['_pdo'] = new PDO("sqlite:$path");
        $GLOBALS['_pdo']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $GLOBALS['_pdo']->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $GLOBALS['_pdo']->exec("PRAGMA journal_mode=WAL");
        $GLOBALS['_pdo']->exec("PRAGMA foreign_keys=ON");
    }
    return $GLOBALS['_pdo'];
}

function db_query($sql, $params = []) {
    $pdo = getDB();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function db_exec($sql, $params = []) {
    $pdo = getDB();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

function db_fetch($stmt) {
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_fetch_all($stmt) {
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function db_count($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetchColumn();
}

function db_last_id() {
    return getDB()->lastInsertId();
}

function db_error() {
    return getDB()->errorInfo()[2];
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
