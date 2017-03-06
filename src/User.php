<?php
    class User
    {
        private $first_name;
        private $last_name;
        private $id;

        function __construct($first_name, $last_name, $id = null)
        {
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->id = $id;
        }

        function setFirstName($new_first_name)
        {
            $this->first_name = (string) $new_first_name;
        }

        function getFirstName()
        {
            return $this->first_name;
        }

        function setLastName($new_last_name)
        {
            $this->last_name = (string) $new_last_name;
        }

        function getLastName()
        {
            return $this->last_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO authors (first_name, last_name) VALUES ('{$this->getFirstName()}', '{$this->getLastName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors ORDER BY last_name ASC;");
            $authors = array();
            foreach($returned_authors as $author) {
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
                $id = $author['id'];
                $new_author = new Author($first_name, $last_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        static function findAuthor($search_first, $search_last)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_first = $author->getFirstName();
                $author_last = $author->getLastName();
                if (strtolower($author_first) == strtolower($search_first) && strtolower($author_last) == strtolower($search_last)) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        function update($new_first_name, $new_last_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET first_name = '{$new_first_name}', last_name = '{$new_last_name}' WHERE id = {$this->getId()};");
            $this->setFirstName($new_first_name);
            $this->setLastName($new_last_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->getId()};");
        }

        function removeBook($book)
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$book->getId()} and author_id = {$this->getId()};");
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$book->getId()}, {$this->getId()});");
        }

        function getBooks()
        {
            $return_books = $GLOBALS['DB']->query("SELECT books.* FROM authors JOIN authors_books ON (authors.id = authors_books.author_id) JOIN books ON (authors_books.book_id = books.id) WHERE authors.id = {$this->getId()};");

            $books = array();

            foreach ($return_books as $book){
                $name = $book['title'];
                $genre = $book['genre'];
                $ISBN = $book['ISBN'];
                $total = $book['total'];
                $available = $book['available'];
                $checked_out = $book['checked_out'];
                $return_id = $book['id'];
                $new_book = new Book($name, $genre, $ISBN, $total, $available, $checked_out, $return_id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function searchFor($search_term)
        {
            $matches = array();
            $search_term = preg_replace("/[^[:alnum:][:space:]]/u", '', $search_term);
            $search_term = explode(" ", strtolower($search_term));
            $end = end($search_term);

            $query = $GLOBALS['DB']->query("SELECT * FROM authors WHERE first_name = '{$search_term[0]}' OR last_name = '{$end}' OR first_name = '{$end}';");
            foreach ($query as $match) {
                $return_first = $match['first_name'];
                $return_last = $match['last_name'];
                $return_enroll = $match['enroll_date'];
                $return_id = $match['id'];
                $new_author = new Author($return_first, $return_last, $return_enroll, $return_id);
                array_push($matches, $new_author);
            }
            return $matches;
        }
    }
?>
