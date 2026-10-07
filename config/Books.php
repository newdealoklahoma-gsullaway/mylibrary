<?php

declare(strict_types=1);

/*
 * Books data access.
 *
 * ASSUMED SCHEMA (adjust to match your database):
 *   books      id, title, author_id, publisher_id, book_type_id, published_year
 *   authors    id, name
 *   publishers id, name
 *   book_types id, name
 */
class Books extends Database
{
    private const TABLE = 'books';

    /** Rows for the main table (joined display names). */
    public function listForTable(): array
    {
        return $this->all(
            'SELECT b.book_id,
                    b.title,
                    b.publication_year,
                    a.display_name AS author,
                    p.publisher_name AS publisher,
                    t.type_name AS type
               FROM tblbooks b
          LEFT JOIN tblauthors    a ON a.author_id = b.book_id
          LEFT JOIN tblpublishers p ON p.publisher_id = b.book_id
          LEFT JOIN tblbook_types t ON t.type_id = b.book_id
           ORDER BY b.title'
        );
    }

    public function get(int $id): ?array
    {
        return $this->find(self::TABLE, $id);
    }

    public function search(string $term): array
    {
        return $this->all(
            'SELECT * FROM books WHERE title LIKE :q ORDER BY title',
            ['q' => "%{$term}%"]
        );
    }

    public function create(array $data): int
    {
        return (int) $this->insert(self::TABLE, $data);
    }

    public function save(int $id, array $data): int
    {
        return $this->update(self::TABLE, $data, ['id' => $id]);
    }

    public function remove(int $id): int
    {
        return $this->delete(self::TABLE, ['id' => $id]);
    }
}