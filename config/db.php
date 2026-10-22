<?php
/**
 * Database Connection & Configuration
 * Royal Aura Luxury Jewellery
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'jewellery_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO Database Connection
 * @return PDO
 */
function get_db_connection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production, avoid leaking details; for local dev show clean message
            die("Database Connection Error: " . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}

/**
 * Fetch a setting by key
 * @param string $key
 * @param string $default
 * @return string
 */
function get_setting($key, $default = '') {
    static $settingsCache = null;
    if ($settingsCache === null) {
        $pdo = get_db_connection();
        try {
            $stmt = $pdo->query("SELECT * FROM settings");
            $settingsCache = [];
            while ($row = $stmt->fetch()) {
                $k = $row['key'] ?? $row['key_name'] ?? null;
                $v = $row['value'] ?? $row['key_value'] ?? null;
                if ($k !== null) {
                    $settingsCache[$k] = $v;
                }
            }
        } catch (Exception $e) {
            return $default;
        }
    }
    return isset($settingsCache[$key]) ? $settingsCache[$key] : $default;
}

/**
 * Update or insert a setting
 * @param string $key
 * @param string $value
 * @return bool
 */
function update_setting($key, $value) {
    $pdo = get_db_connection();
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM settings")->fetchAll(PDO::FETCH_COLUMN);
        $keyCol = in_array('key', $cols) ? '`key`' : '`key_name`';
        $valCol = in_array('value', $cols) ? '`value`' : '`key_value`';
        $stmt = $pdo->prepare("INSERT INTO settings ($keyCol, $valCol) VALUES (?, ?) ON DUPLICATE KEY UPDATE $valCol = VALUES($valCol)");
        return $stmt->execute([$key, $value]);
    } catch (Exception $e) {
        return false;
    }
}
