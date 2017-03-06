<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Comment.php";
    require_once "src/User.php";
    require_once "src/Category.php";
    require_once "src/Thread.php";

    $server = 'mysql:host=localhost:8889;dbname=forum_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
            Patron::deleteAll();
        }

        function testGetTitle()
        {
            //Arrange
            $title = "History of the Vietnam War";
            $genre = "History";
            $ISBN = "123456789102";
            $total = 3;
            $available = 3;
            $checked_out = 0;
            $id = null;

            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out, $id);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);

        }

        function testSetTitle()
        {
            //Arrange
            $title = "1984";
            $genre = "Non-fiction";
            $ISBN = "123456789101";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $id = null;

            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out, $id);

            //Act
            $test_book->setTitle("Trumps Reich");
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals("Trumps Reich", $result);
        }

        function testGetId()
        {
            //Arrange
            $title = "War of the Worlds";
            $genre = "Science Fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $id = 84;

            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out, $id);

            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();


            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book], $result);
        }

        function testUpdate()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $test_total = 1;

            //Act
            $test_book->update($test_total);

            //Assert
            $this->assertEquals(4, $test_book->getTotal());
        }

        function testDeleteBook()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();


            //Act
            $test_book->delete();

            //Assert
            $this->assertEquals([$test_book2], Book::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();

            //Act
            Book::deleteAll();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();

            //Act
            $result = Book::find($test_book->getId());

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testAddAuthor()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $first_name = "Mark";
            $last_name = "Johnson";

            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author]);
        }

        function testGetAuthors()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 0;
            $checked_out = 3;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $first_name = "Mark";
            $last_name = "Johnson";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();

            $first_name2 = "Donald";
            $last_name2 = "Glut";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->addAuthor($test_author2);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author, $test_author2]);
        }



    }
?>
