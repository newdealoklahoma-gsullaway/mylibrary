<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database (PDO base class)
|--------------------------------------------------------------------------
|
| Base class for anything that talks to the database. Extend it:
|
|   class Books extends Database
|   {
|       public function search(string $term): array
|       {
|           return $this->all(
|               'SELECT * FROM books WHERE title LIKE :q',
|               ['q' => "%{$term}%"]
|           );
|       }
|   }
|
| All instances share ONE PDO connection, opened on first query.
| Values are always bound as parameters. Table/column names can't be
| bound, so they are validated and backtick-quoted (see ident()).
|
| $where is an array of column => value, joined with AND.
| A null value becomes "IS NULL".
|
*/

class Database
{
    private static ?PDO $pdo = null;


    /*
    |----------------------------------------------------------------------
    | Connection
    |----------------------------------------------------------------------
    */

    protected function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host    = (string) env('DB_HOST', 'localhost');
        $name    = (string) env('DB_NAME', '');
        $user    = (string) env('DB_USER', '');
        $pass    = (string) env('DB_PASS', '');
        $charset = (string) env('DB_CHARSET', 'utf8mb4');

        if ($name === '' || $user === '') {
            die('Database configuration is incomplete.');
        }

        try {

            self::$pdo = new PDO(
                "mysql:host={$host};dbname={$name};charset={$charset}",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );

        } catch (PDOException $e) {

            if (APP_DEBUG) {
                die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
            }

            error_log('DB connection failed: ' . $e->getMessage());
            die('Database connection failed.');
        }

        return self::$pdo;
    }


    /*
    |----------------------------------------------------------------------
    | Internal helpers
    |----------------------------------------------------------------------
    */

    /** Validate and backtick-quote a table or column name. */
    protected function ident(string $name): string
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            throw new InvalidArgumentException("Invalid identifier: {$name}");
        }

        return "`{$name}`";
    }

    /**
     * Build "col = :w_col AND col2 IS NULL" plus its params.
     * The w_ prefix keeps WHERE params from colliding with SET params.
     *
     * @return array{0:string,1:array<string,mixed>}
     */
    protected function where(array $where): array
    {
        $parts  = [];
        $params = [];

        foreach ($where as $col => $val) {

            $quoted = $this->ident((string) $col);

            if ($val === null) {
                $parts[] = "{$quoted} IS NULL";
                continue;
            }

            $parts[]            = "{$quoted} = :w_{$col}";
            $params["w_{$col}"] = $val;
        }

        return [implode(' AND ', $parts), $params];
    }


    /*
    |----------------------------------------------------------------------
    | Raw queries (joins, searches, anything custom)
    |----------------------------------------------------------------------
    */

    public function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function all(string $sql, array $params = []): array
    {
        return $this->run($sql, $params)->fetchAll();
    }

    public function row(string $sql, array $params = []): ?array
    {
        $row = $this->run($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    public function value(string $sql, array $params = []): mixed
    {
        $val = $this->run($sql, $params)->fetchColumn();

        return $val === false ? null : $val;
    }


    /*
    |----------------------------------------------------------------------
    | CRUD
    |----------------------------------------------------------------------
    */

    /** Returns the new auto-increment id. */
    public function insert(string $table, array $data): string
    {
        if ($data === []) {
            throw new InvalidArgumentException('insert() needs data.');
        }

        $cols         = array_map([$this, 'ident'], array_keys($data));
        $placeholders = array_map(fn($c) => ":{$c}", array_keys($data));

        $this->run(
            'INSERT INTO ' . $this->ident($table)
            . ' (' . implode(', ', $cols) . ')'
            . ' VALUES (' . implode(', ', $placeholders) . ')',
            $data
        );

        return $this->pdo()->lastInsertId();
    }

    /** Returns rows affected. */
    public function update(string $table, array $data, array $where): int
    {
        // An empty WHERE would update every row, so refuse it.
        if ($data === [] || $where === []) {
            throw new InvalidArgumentException('update() needs data and a where clause.');
        }

        $sets   = [];
        $params = [];

        foreach ($data as $col => $val) {
            $sets[]             = $this->ident((string) $col) . " = :s_{$col}";
            $params["s_{$col}"] = $val;
        }

        [$whereSql, $whereParams] = $this->where($where);

        return $this->run(
            'UPDATE ' . $this->ident($table)
            . ' SET ' . implode(', ', $sets)
            . ' WHERE ' . $whereSql,
            $params + $whereParams
        )->rowCount();
    }

    /** Returns rows affected. */
    public function delete(string $table, array $where): int
    {
        if ($where === []) {
            throw new InvalidArgumentException('delete() needs a where clause.');
        }

        [$whereSql, $params] = $this->where($where);

        return $this->run(
            'DELETE FROM ' . $this->ident($table) . ' WHERE ' . $whereSql,
            $params
        )->rowCount();
    }

    public function find(string $table, int|string $id, string $pk = 'id'): ?array
    {
        [$whereSql, $params] = $this->where([$pk => $id]);

        return $this->row(
            'SELECT * FROM ' . $this->ident($table) . ' WHERE ' . $whereSql . ' LIMIT 1',
            $params
        );
    }

    public function count(string $table, array $where = []): int
    {
        $sql    = 'SELECT COUNT(*) FROM ' . $this->ident($table);
        $params = [];

        if ($where !== []) {
            [$whereSql, $params] = $this->where($where);
            $sql .= ' WHERE ' . $whereSql;
        }

        return (int) $this->value($sql, $params);
    }


    /*
    |----------------------------------------------------------------------
    | Transactions
    |----------------------------------------------------------------------
    |
    | $db->transaction(function () use ($db) {
    |     $id = $db->insert('books', [...]);
    |     $db->insert('book_authors', ['book_id' => $id, ...]);
    | });
    |
    */

    public function transaction(callable $fn): mixed
    {
        $pdo = $this->pdo();

        $pdo->beginTransaction();

        try {

            $result = $fn();
            $pdo->commit();

            return $result;

        } catch (Throwable $e) {

            $pdo->rollBack();
            throw $e;
        }
    }
}