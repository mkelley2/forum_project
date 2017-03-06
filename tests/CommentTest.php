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
        //
        // function testSave()
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
        //
        //     //Act
        //     $result = Book::getAll();
        //
        //     //Assert
        //     $this->assertEquals([$test_book], $result);
        // }
        //
        // function testUpdate()
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
        //     $test_total = 1;
        //
        //     //Act
        //     $test_book->update($test_total);
        //
        //     //Assert
        //     $this->assertEquals(4, $test_book->getTotal());
        // }
        //
        // function testDeleteBook()
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
        //     $title2 = "Stas Wars: The Empire Strikes Back";
        //     $genre2 = "Non-fiction";
        //     $ISBN2 = "123456789104";
        //     $total2 = 3;
        //     $available2 = 0;
        //     $checked_out2 = 3;
        //     $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
        //     $test_book2->save();
        //
        //
        //     //Act
        //     $test_book->delete();
        //
        //     //Assert
        //     $this->assertEquals([$test_book2], Book::getAll());
        // }
        //
        // function testGetAll()
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
        //     $title2 = "Stas Wars: The Empire Strikes Back";
        //     $genre2 = "Non-fiction";
        //     $ISBN2 = "123456789104";
        //     $total2 = 3;
        //     $available2 = 0;
        //     $checked_out2 = 3;
        //     $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
        //     $test_book2->save();
        //
        //     //Act
        //     $result = Book::getAll();
        //
        //     //Assert
        //     $this->assertEquals([$test_book, $test_book2], $result);
        // }
        //
        // function testDeleteAll()
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
        //     $title2 = "Stas Wars: The Empire Strikes Back";
        //     $genre2 = "Non-fiction";
        //     $ISBN2 = "123456789104";
        //     $total2 = 3;
        //     $available2 = 0;
        //     $checked_out2 = 3;
        //     $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
        //     $test_book2->save();
        //
        //     //Act
        //     Book::deleteAll();
        //
        //     //Assert
        //     $result = Book::getAll();
        //     $this->assertEquals([], $result);
        // }
        //
        // function testFind()
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
        //     $title2 = "Stas Wars: The Empire Strikes Back";
        //     $genre2 = "Non-fiction";
        //     $ISBN2 = "123456789104";
        //     $total2 = 3;
        //     $available2 = 0;
        //     $checked_out2 = 3;
        //     $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
        //     $test_book2->save();
        //
        //     //Act
        //     $result = Book::find($test_book->getId());
        //
        //     //Assert
        //     $this->assertEquals($test_book, $result);
        // }
        //
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
