<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Comment.php";
    require_once "src/Tag.php";
    require_once "src/Thread.php";
    require_once "src/Category.php";
    require_once "src/User.php";

    $server = 'mysql:host=localhost:8889;dbname=forum_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CommentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Comment::deleteAll();
            Tag::DeleteAll();
        }

        function test_construct()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible!";
            $parent_id = null;
            $score = 345;
            $post_time = " 2005-08-15T15:52:01+00:00";
            $init_comment_id = null;
            $thread_id = null;
            $comment_id = null;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id, $comment_id);

            // Act
            $result1 = $new_comment->getUserId();
            $result2 = $new_comment->getComment();
            $result3 = $new_comment->getparentId();
            $result4 = $new_comment->getScore();
            $result5 = $new_comment->getPostTime();
            $result6 = $new_comment->getInitCommentId();
            $result7 = $new_comment->getThreadId();
            $result8 = $new_comment->getCommentId();

            // Assert
            $this->assertEquals($user_id, $result1);
            $this->assertEquals($comment, $result2);
            $this->assertEquals($parent_id, $result3);
            $this->assertEquals($score, $result4);
            $this->assertEquals($post_time, $result5);
            $this->assertEquals($init_comment_id, $result6);
            $this->assertEquals($thread_id, $result7);
            $this->assertEquals($comment_id, $result8);
        }

        function test_save()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);

            // Act
            $new_comment->save();
            $result = Comment::getAll();
            
            // Assert
            $this->assertEquals($new_comment, $result[0]);
        }

        function test_getAll()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $user_id2 = 5;
            $comment2 = "I thought the frs was terrible";
            $parent_id2 = 6;
            $score2 = 345;
            $post_time2 = '2005-08-15 15:52:01';
            $init_comment_id2 = 7;
            $thread_id2 = 8;
            $new_comment2 = new Comment($user_id2, $comment2, $parent_id2, $score2, $post_time2, $init_comment_id2, $thread_id2);
            $new_comment2->save();

            // Act
            $result = Comment::getAll();

            // Assert
            $this->assertEquals([$new_comment, $new_comment2], $result);
        }

        function test_deleteAll()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $user_id2 = 5;
            $comment2 = "I thought the frs was terrible";
            $parent_id2 = 6;
            $score2 = 345;
            $post_time2 = '2005-08-15 15:52:01';
            $init_comment_id2 = 7;
            $thread_id2 = 8;
            $new_comment2 = new Comment($user_id2, $comment2, $parent_id2, $score2, $post_time2, $init_comment_id2, $thread_id2);
            $new_comment2->save();

            // Act
            Comment::deleteAll();
            $result = Comment::getAll();

            // Assert
            $this->assertEquals([], $result);
        }

        function test_updateComment()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $new_text = "Well I guess it was not that bad but I am still displeased";

            // Act
            $new_comment->updateComment($new_text);

            // Assert
            $this->assertEquals($new_text, $new_comment->getComment());
        }

        function test_updateScore()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $new_score =  425;

            // Act
            $new_comment->updateScore($new_score);

            // Assert
            $this->assertEquals($new_score, $new_comment->getScore());
        }

        function test_deleteComment()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $user_id2 = 7;
            $comment2 = "I like turtles";
            $parent_id2 = 8;
            $score2 = 315;
            $post_time2 = '2007-08-15 15:52:01';
            $init_comment_id2 = 5;
            $thread_id2 = 9;
            $new_comment2 = new Comment($user_id2, $comment2, $parent_id2, $score2, $post_time2, $init_comment_id2, $thread_id2);
            $new_comment2->save();

            // Act
            $dead = $new_comment->delete();
            $result = Comment::getAll();

            // Assert
            $this->assertEquals([$new_comment2], $result);
        }

        function test_findComment()
        {
            // Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $user_id2 = 7;
            $comment2 = "I like turtles";
            $parent_id2 = 8;
            $score2 = 315;
            $post_time2 = '2007-08-15 15:52:01';
            $init_comment_id2 = 5;
            $thread_id2 = 9;
            $new_comment2 = new Comment($user_id2, $comment2, $parent_id2, $score2, $post_time2, $init_comment_id2, $thread_id2);
            $new_comment2->save();

            // Act
            $new_comment->find($new_comment2->getInitCommentId());
            $result = $new_comment2;

            // Assert
            $this->assertEquals($new_comment2, $result);
        }


        function testAddTag()
        {
            //Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();


            $tag = "#notmyfave";
            $test_tag = new Tag($tag);
            $test_tag->save();

            //Act
            $new_comment->addTag($test_tag);

            //Assert
            $this->assertEquals($new_comment->getTags(), [$test_tag]);
        }

        function testGetTags()
        {
            //Arrange
            $user_id = 5;
            $comment = "I thought the frs was terrible";
            $parent_id = 6;
            $score = 345;
            $post_time = '2005-08-15 15:52:01';
            $init_comment_id = 7;
            $thread_id = 8;
            $new_comment = new Comment($user_id, $comment, $parent_id, $score, $post_time, $init_comment_id, $thread_id);
            $new_comment->save();

            $tag = "#notmyfave";
            $test_tag = new Tag($tag);
            $test_tag->save();


            $user_id2 = 8;
            $comment2 = "Matt preforms miracles";
            $parent_id2 = 0;
            $score2 = 456;
            $post_time2 = '2007-08-15 15:52:01';
            $init_comment_id2 = 10;
            $thread_id2 = 34;
            $new_comment2 = new Comment($user_id2, $comment2, $parent_id2, $score2, $post_time2, $init_comment_id2, $thread_id2);
            $new_comment2->save();

            $tag2 = "#thebest";
            $test_tag2 = new Tag($tag2);
            $test_tag2->save();


            //Act
            $new_comment->addTag($test_tag);
            $new_comment2->addTag($test_tag2);

            //Assert
            $this->assertEquals($new_comment->getTags(), [$test_tag]);
        }
    }
?>
