<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Comment.php";

    $server = 'mysql:host=localhost:8889;dbname=forum_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CommentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Comment::deleteAll();
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
            // var_dump($new_comment);
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

        
        // function testAddAuthor()
        // {
        //     //Arrange
        //     $title = "War on Terror Revisited: Trumps America";
        //     $genre = "Non-fiction";
        //     $ISBN = "123456789104";
        //     $total = 3;
        //     $available = 0;
        //     $checked_out = 3;
        //     $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
        //     $test_book->save();
        //
        //     $first_name = "Mark";
        //     $last_name = "Johnson";
        //
        //     $test_author = new Author($first_name, $last_name);
        //     $test_author->save();
        //
        //     //Act
        //     $test_book->addAuthor($test_author);
        //
        //     //Assert
        //     $this->assertEquals($test_book->getAuthors(), [$test_author]);
        // }
        //
        // function testGetAuthors()
        // {
        //     //Arrange
        //     $title = "War on Terror Revisited: Trumps America";
        //     $genre = "Non-fiction";
        //     $ISBN = "123456789104";
        //     $total = 3;
        //     $available = 0;
        //     $checked_out = 3;
        //     $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
        //     $test_book->save();
        //
        //     $first_name = "Mark";
        //     $last_name = "Johnson";
        //     $test_author = new Author($first_name, $last_name);
        //     $test_author->save();
        //
        //     $title2 = "Stas Wars: The Empire Strikes Back";
        //     $genre2 = "Non-fiction";
        //     $ISBN2 = "123456789104";
        //     $total2 = 3;
        //     $available2 = 0;
        //     $checked_out2 = 3;
        //     $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
        //     $test_book2->save();
        //
        //     $first_name2 = "Donald";
        //     $last_name2 = "Glut";
        //     $test_author2 = new Author($first_name2, $last_name2);
        //     $test_author2->save();
        //
        //     //Act
        //     $test_book->addAuthor($test_author);
        //     $test_book->addAuthor($test_author2);
        //
        //     //Assert
        //     $this->assertEquals($test_book->getAuthors(), [$test_author, $test_author2]);
        // }
        //


    }
?>
