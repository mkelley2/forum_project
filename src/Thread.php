<?php
    Class Thread
    {
        private $post;
        private $category_id;
        private $user_id;
        private $id;

        function __construct($post, $category_id, $user_id, $id = null)
        {
            $this->post = $post;
            $this->category_id = $category_id;
            $this->user_id = $user_id;
            $this->id = $id;
        }

        function getPost()
        {
            return $this->post;
        }

        function setPost($post)
        {
            $this->post = $post;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        function setCategoryId($category_id)
        {
            $this->category_id = $category_id;
        }

        function getUserId()
        {
            return $this->user_id;
        }

        function setUserId($user_id)
        {
            $this->user_id = $user_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO threads (post, category_id, user_id) VALUES ('{$this->getPost()}', {$this->getCategoryId()}, {$this->getUserId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_threads = $GLOBALS['DB']->query("SELECT * FROM threads;");
            $threads = array();
            foreach($returned_threads as $thread) {
                $post = $thread['post'];
                $category_id = $thread['category_id'];
                $user_id = $thread['user_id'];
                $thread_id = $thread['thread_id'];
                $new_thread = new Thread($post, $category_id, $user_id, $thread_id);
                array_push($threads, $new_thread);
            }
            return $threads;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM threads;");
          $GLOBALS['DB']->exec("DELETE FROM threads_tags;");
          $GLOBALS['DB']->exec("DELETE FROM comments");
          $GLOBALS['DB']->exec("DELETE FROM comments_tags");
        }

        static function find($search_id)
        {
            $found_thread = null;
            $threads = Thread::getAll();
            foreach($threads as $thread) {
                $thread_id = $thread->getId();
                if ($thread_id == $search_id) {
                  $found_thread = $thread;
                }
            }
            return $found_thread;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM threads WHERE thread_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM threads_tags WHERE thread_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM comments_tags JOIN comments ON (threads.thread_id = comments.thread_id) JOIN comments_tags ON (comments.comment_id = comments_tags.comment_id) WHERE thread_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM comments WHERE thread_id = {$this->getId()};");
        }
    }

?>
