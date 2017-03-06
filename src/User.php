<?php
    class User
    {
        private $username;
        private $password;
        private $user_photo;
        private $rank;
        private $bio;
        private $location_city;
        private $location_state;
        private $location_country;
        private $user_score;
        private $creation_date;
        private $id;

        function __construct($username, $password, $user_photo, $rank, $bio, $location_city, $location_state, $location_country, $user_score, $creation_date, $id = null)
        {

            $this->username = $username;
            $this->password = $password;
            $this->user_photo = $user_photo;
            $this->rank = $rank;
            $this->bio = $bio;
            $this->location_city = $location_city;
            $this->location_state = $location_state;
            $this->location_country = $location_country;
            $this->user_score = $user_score;
            $this->creation_date = $creation_date;
            $this->id = $id;
        }


        function getUsername(){
            return $this->username;
        }

        function setUsername($username){
            $this->username = $username;
        }

        function getPassword(){
            return $this->password;
        }

        function setPassword($password){
            $this->password = $password;
        }

        function getUser_photo(){
            return $this->user_photo;
        }

        function setUser_photo($user_photo){
            $this->user_photo = $user_photo;
        }

        function getRank(){
            return $this->rank;
        }

        function setRank($rank){
            $this->rank = $rank;
        }

        function getBio(){
            return $this->bio;
        }

        function setBio($bio){
            $this->bio = $bio;
        }

        function getLocation_city(){
            return $this->location_city;
        }

        function setLocation_city($location_city){
            $this->location_city = $location_city;
        }

        function getLocation_state(){
            return $this->location_state;
        }

        function setLocation_state($location_state){
            $this->location_state = $location_state;
        }

        function getLocation_country(){
            return $this->location_country;
        }

        function setLocation_country($location_country){
            $this->location_country = $location_country;
        }

        function getUser_score(){
            return $this->user_score;
        }

        function setUser_score($user_score){
            $this->user_score = $user_score;
        }

        function getCreation_date(){
            return $this->creation_date;
        }

        function setCreation_date($creation_date){
            $this->creation_date = $creation_date;
        }

        function getId(){
            return $this->id;
        }



        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO users (username, password, user_photo, rank, bio, location_city, location_state, location_country, user_score, creation_date) VALUES ( '{$this->getUsername()}', '{$this->getPassword()}', '{$this->getUser_photo()}', {$this->getRank()}, '{$this->getBio()}', '{$this->getLocation_city()}', '{$this->getLocation_state()}', '{$this->getLocation_country()}', {$this->getCreation_date});");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_users = $GLOBALS['DB']->query("SELECT * FROM users;");
            $users = array();
            foreach($returned_users as $user) {
                $username = $user['username'];
                $password = $user['password'];
                $user_photo = $user['user_photo'];
                $rank = $user['rank'];
                $bio = $user['user'];
                $location_city = $user['location_city'];
                $location_state = $user['location_state'];
                $location_country = $user['location_country'];
                $user_score = $user['user_score'];
                $creation_date = $user['creation_date'];
                $id = $user['id'];
                $new_author = new Author($username, $password, $user_photo, $rank, $bio, $location_city, $location_state, $location_country, $user_score, $creation_date, $id);
                array_push($users, $new_user);
            }
            return $users;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM users;");
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
