<?php
    class Comment
    {
        private $user_id;
        private $comment;
        private $parent_id;
        private $score;
        private $post_time;
        private $init_comment_id;
        private $thread_id;
        private $comment_id;

        function __construct($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id, $comment_id = null)
        {
            $this->user_id = $user_id;
            $this->comment = $comment;
            $this->parent_id = $parent_id;
            $this->score = $score;
            $this->post_time = $post_time;
            $this->init_comment_id = $init_comment_id;
            $this->thread_id = $thread_id;
            $this->comment_id = $comment_id;
        }

        function setUserId($new_user_id)
        {
            $this->user_id = (int) $new_user_id;
        }

        function getUserId()
        {
            return $this->user_id;
        }

        function setComment($new_comment)
        {
            $this->comment = (string) $new_comment;
        }

        function getComment()
        {
            return $this->comment;
        }

        function setParentId($new_parent_id)
        {
            $this->parent_id = (string) $new_parent_id;
        }

        function getparentId()
        {
            return $this->parent_id;
        }

        function setCommentId($new_comment_id)
        {
            $this->comment_id = (string) $new_comment_id;
        }


        function setScore($new_score)
        {
            $this->score = (int) $new_score;
        }

        function getScore()
        {
            return $this->score;
        }

        function setPostTime($new_post_time)
        {
            $this->post_time = $new_post_time;
        }

        function getPostTime()
        {
            return $this->post_time;
        }

        function getInitCommentId()
        {
            return $this->init_comment_id;
        }

        function getThreadId()
        {
            $this->thread_id;
        }

        function setThreadId($new_thread_id)
        {
            $this->thread_id;
        }

        function getCommentId()
        {
            return $this->comment_id;
        }


        // CRUD functions

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO comments (user_id, comment, parent_id, comment_id, score, post_time, init_comment_id, thread_id) VALUES ('{$this->getUserId()}',
            '{$this->getComment()}',
            '{$this->getparent_id()}',
            {$this->getScore()},
            {$this->getPostTime()}),
            {$this->getInitCommentId()},
            {$this->getThreadId()};");
            $this->comment_id = $GLOBALS['DB']->lastInsertId();
        }

        function updateComment($new_comment)
        {
                $GLOBALS['DB']->exec("UPDATE comments SET comment = '{$new_comment}' WHERE comment_id = {$this->getCommentId()};");
        }

        function updateScore($new_score)
        {
            $GLOBALS['DB']->exec("UPDATE comments SET score = ( score + {$new_score}) WHERE comment_id = {$this->getCommentId()};");
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM comments WHERE id = {$this->getCommentId()};");

        }

        static function getAll()
        {
            $returned_comments = $GLOBALS['DB']->query("SELECT * FROM comments;");
            $comments = array();
            foreach($returned_comments as $comment) {
                $user = $comment['user_id'];
                $comment = $comment['comment'];
                $parent_id = $comment['parent_id'];
                $score = $comment['score'];
                $post_time = $comment['post_time'];
                $init_comment_id = $comment['init_comment_id'];
                $thread_id = $comment['thread_id'];
                $comment_id = $comment['comment_id'];
                $new_comment = new Comment($user, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id, $comment_id);
                array_push($comments, $new_comment);
            }
            return $comments;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM comments;");
        }

        static function find($search_id)
        {
            $found_comment = null;
            $all_comments = Comment::getAll();
            foreach($all_comments as $comment) {
                $comment_id = $comment->getCommentId();
                if ($comment_id == $search_id) {
                  $found_comment = $comment;
                }
            }
            return $found_comment;
        }

        static function findComment($search_user)
        {
            $found_comment = null;
            $all_comments = Comment::getAll();
            foreach($all_comments as $comment) {
                $user_id = $comment->getUserId();
                if ($user_id == $search_user) {
                  $found_comment = $comment;
                }
            }
            return $found_comment;
        }

        function addTag($tag)
        {
            $GLOBALS['DB']->exec("INSERT INTO comments_tags (comment_id, tag_id) VALUES ({$this->getCommentId()}, {$this->getTagId()});");
        }

        function getTags()
        {
            $return_tags = $GLOBALS['DB']->query("SELECT tags.* FROM comments JOIN comments_tags ON (comments.id = comments_tags.comment_id) JOIN tags ON (comments_tags.tag_id = tags.id) WHERE comments.id = {$this->getCommentId()};");

            $tags = array();

            foreach ($return_tags as $tag){
                $ct_id = $tag['ct_id'];
                $comment_id = $tag['comment_id'];
                $tag_id = $tag['tag_id'];
                $new_tag = new Tag($ct_id, $comment_id, $tag_id);
                array_push($tags, $new_tag);
            }
            return $tags;
        }

        // static function searchFor($search_term)
        // {
        //     $matches = array();
        //     $search_term = explode(" ", strtolower($search_term));
        //
        //     $query = $GLOBALS['DB']->query("SELECT * FROM comments WHERE user_id LIKE '%$search_term%' ORDER BY user_id ASC;");
        //     foreach ($query as $match) {
        //         $user = $match['user_id'];
        //         $comment = $match['comment'];
        //         $parent_id = $match['parent_id'];
        //         $comment_id = $match['comment_id'];
        //         $score = $match['score'];
        //         $post_time = $match['post_time'];
        //         $return_id = $match['id'];
        //         $new_comment = new Comment($user, $comment, $parent_id, $comment_id, $score, $post_time, $return_id);
        //         array_push($matches, $new_comment);
        //     }
        //     return $matches;
        // }
    }
?>
