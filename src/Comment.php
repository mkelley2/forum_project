<?php
    class Comment
    {
        private $title;
        private $genre;
        private $ISBN;
        private $total;
        private $available;
        private $checked_out;
        private $id;

        function __construct($title, $genre, $ISBN, $total, $available, $checked_out, $id = null)
        {
            $this->title = $title;
            $this->genre = $genre;
            $this->ISBN = $ISBN;
            $this->total = $total;
            $this->available = $available;
            $this->checked_out = $checked_out;
            $this->id = $id;
        }

        function setTitle($new_title)
        {
            $this->title = (string) $new_title;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setGenre($new_genre)
        {
            $this->genre = (string) $new_genre;
        }

        function getGenre()
        {
            return $this->genre;
        }

        function setISBN($new_ISBN)
        {
            $this->ISBN = (string) $new_ISBN;
        }

        function getISBN()
        {
            return $this->ISBN;
        }

        function setTotal($new_total)
        {
            $this->total = (string) $new_total;
        }

        function getTotal()
        {
            return $this->total;
        }

        function setAvailable($new_available)
        {
            $this->available = (string) $new_available;
        }

        function getAvailable()
        {
            return $this->available;
        }

        function setCheckedOut($new_checked_out)
        {
            $this->checked_out = (string) $new_checked_out;
        }

        function getCheckedOut()
        {
            return $this->checked_out;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title, genre, ISBN, total, available, checked_out) VALUES ('{$this->getTitle()}',
            '{$this->getGenre()}',
            '{$this->getISBN()}',
            {$this->getTotal()},
            {$this->getAvailable()},
            {$this->getCheckedOut()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_total)
        {
            $GLOBALS['DB']->exec("UPDATE books SET total = (total + {$new_total}), available = (available + {$new_total})  WHERE id = {$this->getId()};");

            $total = $GLOBALS['DB']->query("SELECT total FROM books WHERE id = {$this->getId()}");
            $new_num = null;
            foreach ($total as $num) {
                $new_num = $num['total'];
            }
            $this->setTotal($new_num);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM patrons_books WHERE book_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$this->getId()};");
        }

        function turnIn()
        {
            $GLOBALS['DB']->exec("UPDATE books SET available = (available + 1), checked_out = (checked_out - 1)  WHERE id = {$this->getId()};");

            $available = $GLOBALS['DB']->query("SELECT available, checked_out FROM books WHERE id = {$this->getId()}");
            $new_avail = null;
            $new_check = null;
            foreach ($available as $num) {
                $new_avail = $num['available'];
                $new_check = $num['checked_out'];
            }
            $this->setAvailable($new_avail);
            $this->setCheckedOut($new_check);
        }

        function renew($id)
        {
            $date = date("Y-m-d");
            $due = date('Y-m-d', strtotime($date. ' + 20 days'));
            $GLOBALS['DB']->exec("UPDATE patrons_books SET due_date = '{$due}' WHERE join_id = {$id};");
        }

        function removeBook($id)
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons_books WHERE join_id = {$id}");
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $name = $book['title'];
                $genre = $book['genre'];
                $ISBN = $book['ISBN'];
                $total = $book['total'];
                $available = $book['available'];
                $checked_out = $book['checked_out'];
                $id = $book['id'];
                $new_book = new Book($name, $genre, $ISBN, $total, $available, $checked_out, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM books;");
        }

        static function find($search_id)
        {
            $found_book = null;
            $all_books = Book::getAll();
            foreach($all_books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }

        static function findBook($search_name)
        {
            $found_book = null;
            $all_books = Book::getAll();
            foreach($all_books as $book) {
                $title = $book->getTitle();
                if ($title == $search_name) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$this->getId()}, {$author->getId()});");
        }

        function getAuthors()
        {
            $return_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN authors_books ON (books.id = authors_books.book_id) JOIN authors ON (authors_books.author_id = authors.id) WHERE books.id = {$this->getId()};");

            $authors = array();

            foreach ($return_authors as $author){
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
                $return_id = $author['id'];
                $new_author = new Author($first_name, $last_name, $return_id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function searchFor($search_term)
        {
            $matches = array();
            $search_term = explode(" ", strtolower($search_term));

            $query = $GLOBALS['DB']->query("SELECT * FROM books WHERE title LIKE '%$search_term%' ORDER BY title ASC;");
            foreach ($query as $match) {
                $name = $match['title'];
                $genre = $match['genre'];
                $ISBN = $match['ISBN'];
                $total = $match['total'];
                $available = $match['available'];
                $checked_out = $match['checked_out'];
                $return_id = $match['id'];
                $new_book = new Book($name, $genre, $ISBN, $total, $available, $checked_out, $return_id);
                array_push($matches, $new_book);
            }
            return $matches;
        }
    }
?>
