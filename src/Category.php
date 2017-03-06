<?php
    class Category
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
              $GLOBALS['DB']->exec("INSERT INTO patrons (first_name, last_name) VALUES ('{$this->getFirstName()}', '{$this->getLastName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons ORDER BY last_name ASC;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $first_name = $patron['first_name'];
                $last_name = $patron['last_name'];
                $id = $patron['id'];
                $new_patron = new Patron($first_name, $last_name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function findPatron($search_first, $search_last)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_first = $patron->getFirstName();
                $patron_last = $patron->getLastName();
                if (strtolower($patron_first) == strtolower($search_first) && strtolower($patron_last) == strtolower($search_last)) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function findBooks()
        {
            $found_books = [];
            $return_books = $GLOBALS['DB']->query("SELECT patrons_books.join_id, patrons_books.checkout_date, patrons_books.due_date, books.title, books.id, authors.first_name, authors.last_name FROM patrons join patrons_books ON (patrons.id = patrons_books.patron_id) join books on books.id = patrons_books.book_id join authors_books ON (books.id = authors_books.book_id) join authors ON (authors_books.author_id = authors.id) where patrons.id = {$this->getId()};");
            foreach ($return_books as $book){
                $title = $book['title'];
                $id = $book['id'];
                $join_id = $book['join_id'];
                $first_name = $book['first_name'];
                $last_name = $book['last_name'];
                $due = $book['due_date'];
                $check = $book['checkout_date'];
                $new_book = array('title'=>$title, 'join_id'=>$join_id, 'id'=>$id, 'first_name'=>$first_name, 'last_name'=>$last_name, 'due'=>$due, 'check'=>$check);
                array_push($found_books, $new_book);
            }
            return $found_books;
        }

        function update($new_first_name, $new_last_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET first_name = '{$new_first_name}', last_name = '{$new_last_name}' WHERE id = {$this->getId()};");
            $this->setFirstName($new_first_name);
            $this->setLastName($new_last_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM patrons_books WHERE patron_id = {$this->getId()};");
        }

        function addBook($book)
        {
            $available = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$book->getId()};");
            $number_available = null;
            $number_checked_out = null;
            foreach ($available as $novel) {
                $number_available = $novel['available'];
                $number_checked_out = $novel['checked_out'];
            }
            $number_available = $number_available - 1;
            $number_checked_out = $number_checked_out + 1;

            $date = date("Y-m-d");
            $due = date('Y-m-d', strtotime($date. ' + 14 days'));

            $GLOBALS['DB']->exec("UPDATE books SET available = {(int) $number_available}, checked_out = {(int) $number_checked_out}");

            $GLOBALS['DB']->exec("INSERT INTO patrons_books (book_id, patron_id, checkout_date, due_date) VALUES ({$book->getId()}, {$this->getId()}, '{$date}', '{$due}');");
        }

        function getBooks()
        {
            $return_books = $GLOBALS['DB']->query("SELECT books.* FROM patrons JOIN patrons_books ON (patrons.id = patrons_books.patron_id) JOIN books ON (patrons_books.book_id = books.id) WHERE patrons.id = {$this->getId()};");
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

            $query = $GLOBALS['DB']->query("SELECT * FROM patrons WHERE first_name = '{$search_term[0]}' OR last_name = '{$end}' OR first_name = '{$end}';");
            foreach ($query as $match) {
                $return_first = $match['first_name'];
                $return_last = $match['last_name'];
                $return_id = $match['id'];
                $new_patron = new Patron($return_first, $return_last, $return_id);
                array_push($matches, $new_patron);
            }
            return $matches;
        }
    }
?>
