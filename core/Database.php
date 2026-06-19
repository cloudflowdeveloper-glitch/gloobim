<?php

namespace Core;

class Database
{
    protected static ?\mysqli $connection = null;

    public static function connect(array $config = []): \mysqli
    {
        if (static::$connection !== null) {
            return static::$connection;
        }

        $config = $config ?: Config::get('database.connections.' . Config::get('database.default'), []);

        $host = $config['host'] ?? 'localhost';
        $port = (int) ($config['port'] ?? 3306);
        $database = $config['database'] ?? 'dttube';
        $username = $config['username'] ?? 'root';
        $password = $config['password'] ?? '';

        static::$connection = new \mysqli($host, $username, $password, $database, $port);

        if (static::$connection->connect_error) {
            throw new \Exception("Database connection failed: " . static::$connection->connect_error);
        }

        static::$connection->set_charset('utf8mb4');

        return static::$connection;
    }

    public static function connection(): \mysqli
    {
        return static::$connection ?? static::connect();
    }

    public static function isConnected(): bool
    {
        return static::$connection !== null;
    }

    public static function query(string $sql, array $params = []): array
    {
        $conn = static::connection();
        $stmt = static::prepareStatement($conn, $sql, $params);
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    public static function queryOne(string $sql, array $params = []): ?array
    {
        $conn = static::connection();
        $stmt = static::prepareStatement($conn, $sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function execute(string $sql, array $params = []): int
    {
        $conn = static::connection();
        $stmt = static::prepareStatement($conn, $sql, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public static function insert(string $table, array $data): int
    {
        $conn = static::connection();
        $columns = implode(', ', array_map(fn($col) => "`{$col}`", array_keys($data)));
        $types = '';
        $values = [];
        foreach ($data as $val) {
            if (is_int($val)) { $types .= 'i'; }
            elseif (is_float($val)) { $types .= 'd'; }
            else { $types .= 's'; }
            $values[] = $val;
        }
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = static::prepareStatement($conn, $sql, $values, $types);
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setClause = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $sql = "UPDATE `{$table}` SET {$setClause} WHERE {$where}";
        $allParams = array_merge(array_values($data), $whereParams);
        return static::execute($sql, $allParams);
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        return static::execute($sql, $params);
    }

    public static function beginTransaction(): void
    {
        static::connection()->begin_transaction();
    }

    public static function commit(): void
    {
        static::connection()->commit();
    }

    public static function rollback(): void
    {
        static::connection()->rollback();
    }

    public static function raw(string $sql): bool
    {
        return static::connection()->query($sql);
    }

    protected static function prepareStatement(\mysqli $conn, string $sql, array $params = [], string $types = ''): \mysqli_stmt
    {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception("SQL prepare error: " . $conn->error . " | SQL: " . $sql);
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = '';
                foreach ($params as $val) {
                    if (is_int($val)) { $types .= 'i'; }
                    elseif (is_float($val)) { $types .= 'd'; }
                    else { $types .= 's'; }
                }
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }
}
