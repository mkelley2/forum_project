
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
    
        protected function tearDown()
        {
            User::deleteAll();
        }
    
        function testGetUserName()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testGetUserName = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
        
            //Act
            $result = $testGetUserName->getUserName();
        
            //Assert
            $this->assertEquals($username, $result);
        
        }
        
        function testSetUserName()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testGetUserName = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
        
            //Act
            $testGetUserName->setUsername("Bob");
            $result = $testGetUserName->getUserName();
        
            //Assert
            $this->assertEquals("Bob", $result);
        }
    
        function testGetId()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
            $id = 1;
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create, $id);
        
            //Act
            $result = $testUser->getId();
        
            //Assert
            $this->assertEquals($id, $result);
        }
        
        function testSave()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            //Act
            $result = User::getAll();
        
            //Assert
            $this->assertEquals($testUser, $result[0]);
        }
        
        function testUpdate()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            $new_city = "Mike";
            $new_state = "Mike";
            $new_country = "Mike";
            $new_bio = "Mike";
        
            //Act
            $testUser->update($new_city, $new_state, $new_country, $new_bio);
        
            //Assert
            $this->assertEquals($new_city, $testUser->getLocation_city());
        }
        
        function testDeleteUser()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            $username2 = "John";
            $password2 = "pwq";
            $user_photo2 = "imgur.com/fsdfs";
            $rank2 = "normal";
            $bio2 = "I am John";
            $city2 = "Seatlle";
            $state2 = "WA";
            $country2 = "USA";
            $score2 = 12324;
            $create2 = "2017-02-01 12:01:00";
        
            $testUser2 = new User($username2, $password2, $user_photo2, $rank2, $bio2, $city2, $state2, $country2, $score2, $create2);
            $testUser2->save();
        
            //Act
            $testUser->delete();
        
            //Assert
            $this->assertEquals([$testUser2], User::getAll());
        }
        
        function testGetAll()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            $username2 = "John";
            $password2 = "pwq";
            $user_photo2 = "imgur.com/fsdfs";
            $rank2 = "normal";
            $bio2 = "I am John";
            $city2 = "Seatlle";
            $state2 = "WA";
            $country2 = "USA";
            $score2 = 12324;
            $create2 = "2017-02-01 12:01:00";
        
            $testUser2 = new User($username2, $password2, $user_photo2, $rank2, $bio2, $city2, $state2, $country2, $score2, $create2);
            $testUser2->save();
        
            //Act
            $result = User::getAll();
        
            //Assert
            $this->assertEquals([$testUser, $testUser2], $result);
        }
        
        function testDeleteAll()
        {
            //Arrange
            $username = "Mark";
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            $username2 = "John";
            $password2 = "pwq";
            $user_photo2 = "imgur.com/fsdfs";
            $rank2 = "normal";
            $bio2 = "I am John";
            $city2 = "Seatlle";
            $state2 = "WA";
            $country2 = "USA";
            $score2 = 12324;
            $create2 = "2017-02-01 12:01:00";
        
            $testUser2 = new User($username2, $password2, $user_photo2, $rank2, $bio2, $city2, $state2, $country2, $score2, $create2);
            $testUser2->save();
        
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
            $password = "pw";
            $user_photo = "imgur.com/dasdasd";
            $rank = "admin";
            $bio = "I am Mark";
            $city = "Portland";
            $state = "OR";
            $country = "USA";
            $score = 372193;
            $create = "2017-01-01 12:00:00";
        
            $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
            $testUser->save();
        
            $username2 = "John";
            $password2 = "pwq";
            $user_photo2 = "imgur.com/fsdfs";
            $rank2 = "normal";
            $bio2 = "I am John";
            $city2 = "Seatlle";
            $state2 = "WA";
            $country2 = "USA";
            $score2 = 12324;
            $create2 = "2017-02-01 12:01:00";
        
            $testUser2 = new User($username2, $password2, $user_photo2, $rank2, $bio2, $city2, $state2, $country2, $score2, $create2);
            $testUser2->save();
        
            //Act
            $result = User::find($testUser->getId());
        
            //Assert
            $this->assertEquals($testUser, $result);
        }
        
        function testLogin(){
          $username = "Mark";
          $password = password_hash('password', CRYPT_BLOWFISH);
          $user_photo = "imgur.com/dasdasd";
          $rank = "admin";
          $bio = "I am Mark";
          $city = "Portland";
          $state = "OR";
          $country = "USA";
          $score = 372193;
          $create = "2017-01-01 12:00:00";
    
          $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
          $testUser->save();
          
          $user = User::logIn($username, 'password');
          
          $this->assertEquals($user, $testUser);
          
        }
        
        function testLoginFail(){
          $username = "Mark";
          $password = password_hash('password', CRYPT_BLOWFISH);
          $user_photo = "imgur.com/dasdasd";
          $rank = "admin";
          $bio = "I am Mark";
          $city = "Portland";
          $state = "OR";
          $country = "USA";
          $score = 372193;
          $create = "2017-01-01 12:00:00";
    
          $testUser = new User($username, $password, $user_photo, $rank, $bio, $city, $state, $country, $score, $create);
          $testUser->save();
          
          $user = User::logIn($username, 'password1');
          
          $this->assertEquals($user, false);
          
        }
        
    }

?>
