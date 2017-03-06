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


    class UserTest extends PHPUnit_Framework_TestCase
    {

        // protected function tearDown()
        // {
        //     Author::deleteAll();
        //     Book::deleteAll();
        //     User::deleteAll();
        // }

        function testGetUserName()
        {
            //Arrange
            $username = "Mark";

            $testGetUserName = new User($username);

            //Act
            $result = $testGetUserNamee->getUserName();

            //Assert
            $this->assertEquals($username, $result);

        }

        function testSetUserName()
        {
            //Arrange
            $username = "Mark";


            $testSetUserName = new User($username);

            //Act
            $testSetUserName->setUsername("Bob");
            $result = $testsetUsername->getUserName();

            //Assert
            $this->assertEquals("Bob", $result);
        }

        // function testGetUserPassword()
        // {
        //     //Arrange
        //     $password = "andand";
        //
        //
        //     $testGetPassword = new User($password);
        //
        //     //Act
        //     $result = $testGetPassword->getPassword();
        //
        //     //Assert
        //     $this->assertEquals($password, $result);
        //
        // }
        //
        // function testSetUserPassword()
        // {
        //     //Arrange
        //     $password = "andand";
        //
        //
        //     $testSetPassword = new User($password);
        //     $new_password = "copy";
        //     //Act
        //     $testGetFirstName->setPassword("copy");
        //     $result = $testGetFirstName->getPassword();
        //
        //     //Assert
        //     $this->assertEquals("copy", $result);
        // }

        function testGetId()
        {
            //Arrange
            $username = "Mark";
            $id = 1;

            $testGetFirstName = new User($username, $id);

            //Act
            $result = $testGetFirstName->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            //Arrange
            $username = "Mark";

            $testGetFirstName = new User($username);
            $testGetFirstName->save();

            //Act
            $result = User::getAll();

            //Assert
            $this->assertEquals($testGetFirstName, $result[0]);
        }

        function testUpdate()
        {
            //Arrange
            $username = "Mark";

            $testGetFirstName = new User($username);
            $testGetFirstName->save();

            $new_username = "Mike";

            //Act
            $testGetFirstName->update($new_username);

            //Assert
            $this->assertEquals($new_username, $testGetFirstName->getFirstName());
        }

        function testDeleteUser()
        {
            //Arrange
            $username = "Mark";

            $testGetFirstName = new User($username, $last_name);
            $testGetFirstName->save();

            $username2 = "John";
            $last_name2 = "Johnson";

            $testGetFirstName2 = new User($username2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $testGetFirstName->delete();

            //Assert
            $this->assertEquals([$testGetFirstName2], User::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $username = "Mark";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new User($username, $last_name);
            $testGetFirstName->save();

            $username2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new User($username2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $result = User::getAll();

            //Assert
            $this->assertEquals([$testGetFirstName, $testGetFirstName2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $username = "Mark";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new User($username, $last_name);
            $testGetFirstName->save();

            $username2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new User($username2, $last_name2);
            $testGetFirstName2->save();

            //Act
            User::deleteAll();

            //Assert
            $result = User::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $username = "Mark";
            $enroll_date = "2017-12-12";

            $testGetFirstName = new User($username, $last_name);
            $testGetFirstName->save();

            $username2 = "John";
            $last_name2 = "Johnson";
            $enroll_date2 = "2017-12-12";

            $testGetFirstName2 = new User($username2, $last_name2);
            $testGetFirstName2->save();

            //Act
            $result = User::find($testGetFirstName->getId());

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

            $username = "Mark";

            $test_patron = new User($username, $last_name);
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

            $username = "Mark";

            $test_patron = new User($username, $last_name);
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

            $username = "Mark";

            $testAuthor = new Author($username, $last_name);
            $testAuthor->save();

            $testAuthor->addBook($test_book);

            $username2 = "Jim";
            $last_name2 = "Jackson";

            $testAuthor2 = new Author($username2, $last_name2);
            $testAuthor2->save();

            $testAuthor2->addBook($test_book2);

            $patron_username = "Sam";
            $patron_last_name = "Waters";

            $test_patron = new User($patron_username, $patron_last_name);
            $test_patron->save();

            $test_patron->addBook($test_book);
            $test_patron->addBook($test_book2);

            $date = "2017-03-02";
            $due = "2017-03-16";

            //Act

            $test_patron->findBooks();

            //Assert
            $this->assertEquals($test_patron->findBooks(),array('title'=>$title, 'id'=>$id, 'username'=>$username, 'last_name'=>$last_name, 'due'=> $due, 'check'=>$date));
        }
    }

?>
