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
        private $user_id;

        function __construct($username, $password, $user_photo, $rank, $bio, $location_city, $location_state, $location_country, $user_score, $creation_date, $user_id = null)
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
            $this->user_id = $user_id;
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
            return $this->user_id;
        }



        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO users (username, password, user_photo, rank, bio, location_city, location_state, location_country, user_score, creation_date) VALUES (
                '{$this->getUsername()}',
                '{$this->getPassword()}',
                '{$this->getUser_photo()}',
                '{$this->getRank()}',
                '{$this->getBio()}',
                '{$this->getLocation_city()}',
                '{$this->getLocation_state()}',
                '{$this->getLocation_country()}',
                '{$this->getUser_score()}',
                '{$this->getCreation_date()}'
            );");
            $this->user_id = $GLOBALS['DB']->lastInsertId();
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
                $bio = $user['bio'];
                $location_city = $user['location_city'];
                $location_state = $user['location_state'];
                $location_country = $user['location_country'];
                $user_score = $user['user_score'];
                $creation_date = $user['creation_date'];
                $id = $user['user_id'];
                $new_user = new User($username, $password, $user_photo, $rank, $bio, $location_city, $location_state, $location_country, $user_score, $creation_date, $id);
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
            $found_user = null;
            $users = User::getAll();
            foreach($users as $user) {
                $user_id = $user->getId();
                if ($user_id == $search_id) {
                  $found_user = $user;
                }
            }
            return $found_user;
        }
        
        static function findbyName($search_id)
        {
            $found_user = null;
            $users = User::getAll();
            foreach($users as $user) {
                $user_id = $user->getUsername();
                if ($user_id == $search_id) {
                  $found_user = $user;
                }
            }
            return $found_user;
        }

        function update($city, $state, $country, $bio)
        {
            $GLOBALS['DB']->exec("UPDATE users SET location_city = '{$city}', location_state = '{$state}', location_country = '{$country}', bio = '{$bio}' WHERE user_id = {$this->getId()};");
            $this->setBio($bio);
            $this->setLocation_city($city);
            $this->setLocation_state($state);
            $this->setLocation_country($country);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM users WHERE user_id = {$this->getId()};");
            // $GLOBALS['DB']->exec("DELETE FROM comments WHERE user_id = {$this->getId()};");
            // $GLOBALS['DB']->exec("DELETE FROM threads WHERE user_id = {$this->getId()};");
        }

        function getComments()
        {
            $return_comments = $GLOBALS['DB']->query("SELECT * FROM comments where user_id = {$this->getId()};");

            $comments = array();

            foreach ($return_comments as $comment){
                $user_id = $comment['user_id'];
                $comment_text = $comment['comment'];
                $parent_id = $comment['parent_id'];
                $score = $comment['score'];
                $post_time = $comment['post_time'];
                $init_commit_id = $comment['init_commit_id'];
                $thread_id = $comment['thread_id'];
                $comment_id = $comment['comment_id'];
                $new_comment = new Comment($user_id, $comment_text, $parent_id, $score, $post_time, $init_commit_id, $thread_id, $comment_id);
                array_push($comments, $new_comment);
            }
            return $comments;
        }
        
        function getThreads()
        {
            $return_threads = $GLOBALS['DB']->query("SELECT * FROM threads where user_id = {$this->getId()};");

            $threads = array();

            foreach ($return_threads as $thread){
                $post = $thread['post'];
                $category_id = $thread['category_id'];
                $user_id = $thread['user_id'];
                $post_title = $thread['post_title'];
                $category = $thread['category'];
                $id = $thread['thread_id'];
                $new_thread = new Thread($post, $category_id, $user_id, $post_title, $category, $id);
                array_push($threads, $new_thread);
            }
            return $threads;
        }
        
        static function logIn($username, $password){
          $return_users= $GLOBALS['DB']->query("SELECT * FROM users WHERE username = '{$username}';");
          $users = null;
          
          foreach($return_users as $user){
            if(password_verify($password, $user['password'])){
              $username = $user['username'];
              $password = $user['password'];
              $user_photo = $user['user_photo'];
              $rank = $user['rank'];
              $bio = $user['bio'];
              $location_city = $user['location_city'];
              $location_state = $user['location_state'];
              $location_country = $user['location_country'];
              $user_score = $user['user_score'];
              $creation_date = $user['creation_date'];
              $id = $user['user_id'];
              $new_user = new User($username, $password, $user_photo, $rank, $bio, $location_city, $location_state, $location_country, $user_score, $creation_date, $id);
              $users = $new_user;
            }else{
              return false;
            }
          }
          return $users;
        }
    }
?>
