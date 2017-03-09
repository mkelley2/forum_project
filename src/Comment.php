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

        function getParentId()
        {
            return $this->parent_id;
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
            $this->post_time = (string) $new_post_time;
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
            return $this->thread_id;
        }

        function setThreadId($new_thread_id)
        {
            $this->thread_id = (int) $new_thread_id;
        }

        function getCommentId()
        {
            return $this->comment_id;
        }


        // CRUD functions

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO comments (user_id, comment, parent_id, score, post_time, init_comment_id, thread_id) VALUES
            ({$this->getUserId()},
            '{$this->getComment()}',
            {$this->getParentId()},
            {$this->getScore()},
            '{$this->getPostTime()}',
            {$this->getInitCommentId()},
            {$this->getThreadId()}
            );");
            $this->comment_id = $GLOBALS['DB']->lastInsertId();
        }

        function updateComment($new_text)
        {
                $GLOBALS['DB']->exec("UPDATE comments SET comment = '{$new_text}' WHERE comment_id = {$this->getCommentId()};");
                $this->setComment($new_text);
        }

        function updateScore($new_score)
        {
            $GLOBALS['DB']->exec("UPDATE comments SET score = ( score + {$new_score}) WHERE comment_id = {$this->getCommentId()};");
            $returned_comment = $GLOBALS['DB']->query("SELECT score FROM comments WHERE comment_id = {$this->getCommentId()};");
            $score = null;
            foreach($returned_comment as $comment) {
              $score = $comment[0];
            }
            $this->setScore($score);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM comments WHERE comment_id = {$this->getCommentId()};");

        }

        static function getAll()
        {
            $returned_comments = $GLOBALS['DB']->query("SELECT * FROM comments;");
            $comments = array();
            foreach($returned_comments as $comment) {
                $user = $comment['user_id'];
                $comment_text = $comment['comment'];
                $parent_id = $comment['parent_id'];
                $score = $comment['score'];
                $post_time = $comment['post_time'];
                $init_comment_id = $comment['init_comment_id'];
                $thread_id = $comment['thread_id'];
                $comment_id = $comment['comment_id'];
                $new_comment = new Comment($user, $comment_text, $parent_id, $score, $post_time, $init_comment_id, $thread_id, $comment_id);
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

        // function  addMultiTags($str)
        // {
        //
        //     $str = preg_replace("/,/", "", $str);
        //     $array_tags = explode(" ", $str);
        //
        //     for($i = 0; $i < count($array_tags); $i++)
        //     {
        //         $found_tag = Tag::findByName($array_tags[$i]);
        //         $query = $GLOBALS['DB']->query("SELECT * FROM tags;");
        //         $all_tags = array();
        //
        //         foreach($query as $tag)
        //         {
        //             array_push($all_tags, $tag['tag']);
        //         }
        //
        //         $return = array_search($array_tags[i], $all_tags);
        //
        //         if($return !== false){
        //             $this->addTag($found_tag);
        //         }
        //     }
        // }

        // function  createMultiTags($str)
        // {
        //
        //     $str = preg_replace("/,/", "", $str);
        //     $array_tags = explode(" ", $str);
        //
        //     for($i = 0; $i < count($array_tags); $i++)
        //     {
        //         $query = $GLOBALS['DB']->query("SELECT tag FROM tags;");
        //
        //         $all_tags = array();
        //
        //         foreach($query as $tag)
        //         {
        //             // array_push($all_tags, $tag['tag']);
        //             if($tag['tag']==$array_tags[$i]){
        //                 $found_tag = Tag::findByName($array_tags[$i]);
        //                 // var_dump($found_tag);
        //                 $this->addTag($found_tag);
        //             } else {
        //                 $new_tag = new Tag($array_tags[$i]);
        //                 $new_tag->save();
        //                 $this->addTag($new_tag);
        //             }
        //         }
        //         // var_dump($all_tags);
        //
        //         // $return = array_keys($all_tags, $array_tags[i]);
        //         // if(count($return)>0){
        //         //     $found_tag = Tag::findByName($array_tags[$i]);
        //         //     // var_dump($found_tag);
        //         //     $this->addTag($found_tag);
        //         // } else {
        //         //     $new_tag = new Tag($array_tags[$i]);
        //         //     $new_tag->save();
        //         //     $this->addTag($new_tag);
        //         // }
        //     }
        // }

        function addTag($tag)
        {

            $GLOBALS['DB']->exec("INSERT INTO comments_tags (comment_id, tag_id) VALUES ({$this->getCommentId()}, {$tag->getId()});");
        }

        function getTags()
        {
            $return_tags = $GLOBALS['DB']->query("SELECT tags.* FROM comments JOIN comments_tags ON (comments.comment_id = comments_tags.comment_id) JOIN tags ON (comments_tags.tag_id = tags.tag_id) WHERE comments.comment_id = {$this->getCommentId()};");

            $tags = array();

            foreach ($return_tags as $tag){
                $tag_text = $tag['tag'];
                $tag_id = $tag['tag_id'];
                $new_tag = new Tag($tag_text, $tag_id);
                array_push($tags, $new_tag);
            }
            return $tags;
        }

        static function searchFor($search_term)
        {
          $return_comments = $GLOBALS['DB']->query("SELECT comments.*, threads.category from comments JOIN threads ON (comments.thread_id = threads.thread_id) WHERE comment LIKE '%{$search_term}%';");

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
              $category = $comment['category'];
              $new_comment = array('user_id'=> $user_id, 'comment'=> $comment_text, 'parent_id'=>$parent_id, 'score'=>$score, 'post_time'=>$post_time, 'init_commit_id'=>$init_commit_id, 'thread_id'=>$thread_id, 'comment_id'=>$comment_id, 'category'=>$category);
              array_push($comments, $new_comment);
          }
          return $comments;
        }
    }
?>
