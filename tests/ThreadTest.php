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


    class PatronTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
            Patron::deleteAll();
        }

        function testGetFirstName()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";

            $testGetFirstName = new Patron($first_name, $last_name);

            //Act
            $result = $testGetFirstName->getFirstName();

            //Assert
            $this->assertEquals($first_name, $result);

        }

        function testSetFirstName()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";

            $testGetFirstName = new Patron($first_name, $last_name);

            //Act
            $testGetFirstName->setFirstName("Mike");
            $result = $testGetFirstName->getFirstName();

            //Assert
            $this->assertEquals("Mike", $result);
        }

        function testGetId()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";
            $id = 1;

            $testGetFirstName = new Patron($first_name, $last_name, $id);

            //Act
            $result = $testGetFirstName->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals($testGetFirstName, $result[0]);
        }

        function testUpdate()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            $new_first_name = "Mike";
            $new_last_name = "Smith";

            //Act
            $testGetFirstName->update($new_first_name, $new_last_name);

            //Assert
            $this->assertEquals($new_first_name, $testGetFirstName->getFirstName());
        }

        function testDeletePatron()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            $first_name2 = "John";
            $last_name2 = "Johnson";

            $testGetFirstName2 = new Patron($first_name2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $testGetFirstName->delete();

            //Assert
            $this->assertEquals([$testGetFirstName2], Patron::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            $first_name2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new Patron($first_name2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([$testGetFirstName, $testGetFirstName2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            $first_name2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new Patron($first_name2, $last_name2);
            $testGetFirstName2->save();

            //Act
            Patron::deleteAll();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $first_name = "Mark";
            $last_name = "Johnson";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new Patron($first_name, $last_name);
            $testGetFirstName->save();

            $first_name2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new Patron($first_name2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $result = Patron::find($testGetFirstName->getId());

            //Assert
            $this->assertEquals($testGetFirstName, $result);
        }

        function testAddBook()
        {
            //Arrange
            $title = "1984";
            $genre = "Non-fiction";
            $ISBN = "123456789101";
            $total = 3;
            $available = 1;
            $checked_out = 2;

            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $first_name = "Mark";
            $last_name = "Johnson";

            $test_patron = new Patron($first_name, $last_name);
            $test_patron->save();

            //Act
            $test_patron->addBook($test_book);

            //Assert
            $this->assertEquals([$test_book], $test_patron->getBooks());
        }

        function testGetBooks()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 1;
            $checked_out = 2;
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

            $first_name = "Mark";
            $last_name = "Johnson";

            $test_patron = new Patron($first_name, $last_name);
            $test_patron->save();

            //Act
            $test_patron->addBook($test_book);
            $test_patron->addBook($test_book2);

            //Assert
            $this->assertEquals($test_patron->getBooks(), [$test_book, $test_book2]);
        }

        function testFindBooks()
        {
            //Arrange
            $title = "War on Terror Revisited: Trumps America";
            $genre = "Non-fiction";
            $ISBN = "123456789104";
            $total = 3;
            $available = 1;
            $checked_out = 2;
            $test_book = new Book($title, $genre, $ISBN, $total, $available, $checked_out);
            $test_book->save();

            $id = $test_book->getId();

            $title2 = "Stas Wars: The Empire Strikes Back";
            $genre2 = "Non-fiction";
            $ISBN2 = "123456789104";
            $total2 = 3;
            $available2 = 0;
            $checked_out2 = 3;
            $test_book2 = new Book($title2, $genre2, $ISBN2, $total2, $available2, $checked_out2);
            $test_book2->save();

            $first_name = "Mark";
            $last_name = "Johnson";

            $testAuthor = new Author($first_name, $last_name);
            $testAuthor->save();

            $testAuthor->addBook($test_book);

            $first_name2 = "Jim";
            $last_name2 = "Jackson";

            $testAuthor2 = new Author($first_name2, $last_name2);
            $testAuthor2->save();

            $testAuthor2->addBook($test_book2);

            $patron_first_name = "Sam";
            $patron_last_name = "Waters";

            $test_patron = new Patron($patron_first_name, $patron_last_name);
            $test_patron->save();

            $test_patron->addBook($test_book);
            $test_patron->addBook($test_book2);

            $date = "2017-03-02";
            $due = "2017-03-16";

            //Act

            $test_patron->findBooks();

            //Assert
            $this->assertEquals($test_patron->findBooks(),array('title'=>$title, 'id'=>$id, 'first_name'=>$first_name, 'last_name'=>$last_name, 'due'=> $due, 'check'=>$date));
        }
    }

?>
