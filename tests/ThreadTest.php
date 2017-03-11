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


    class ThreadTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Thread::deleteAll();
            Tag::deleteAll();
        }
       
        function testGetThreadName()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            $id = 2;
       
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category, $id);
       
            //Act
            $result = $testThread->getPost();
       
            //Assert
            $this->assertEquals($post, $result);
       
        }
       
        function testSetThreadName()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            $id = 2;
       
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category, $id);
       
            //Act
            $testThread->setPost("Comment text goes here again");
            $result = $testThread->getPost();
       
            //Assert
            $this->assertEquals("Comment text goes here again", $result);
        }
       
       
        function testGetId()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            $id = 2;
       
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category, $id);
       
            //Act
            $result = $testThread->getId();
       
            //Assert
            $this->assertEquals($id, $result);
        }
       
        function testSave()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
            $testThread->save();
       
            //Act
            $result = Thread::getAll();
       
            //Assert
            $this->assertEquals($testThread, $result[0]);
        }
       
        function testDeleteThread()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
            $testThread->save();
       
            $post2 = "Comment text goes here again";
            $category_id2 = 6;
            $user_id2 = 2;
       
            $testThread2 = new Thread($post2, $category_id2, $user_id2, $post_title, $category);
            $testThread2->save();
       
            //Act
            $testThread->delete();
       
            //Assert
            $this->assertEquals([$testThread2], Thread::getAll());
        }
       
        function testGetAll()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
            $testThread->save();
       
            $post2 = "Comment text goes here again";
            $category_id2 = 6;
            $user_id2 = 2;
       
            $testThread2 = new Thread($post2, $category_id2, $user_id2, $post_title, $category);
            $testThread2->save();
       
            //Act
            $result = Thread::getAll();
       
            //Assert
            $this->assertEquals([$testThread, $testThread2], $result);
        }
       
        function testDeleteAll()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
            $testThread->save();
       
            $post2 = "Comment text goes here again";
            $category_id2 = 6;
            $user_id2 = 2;
       
            $testThread2 = new Thread($post2, $category_id2, $user_id2, $post_title, $category);
            $testThread2->save();
       
            //Act
            Thread::deleteAll();
       
            //Assert
            $result = Thread::getAll();
            $this->assertEquals([], $result);
        }
       
        function testFind()
        {
            //Arrange
            $post = "Comment text goes here";
            $category_id = 5;
            $user_id = 4;
            $post_title = "Great Post";
            $category = "PHP";
            
            $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
            $testThread->save();
       
            $post2 = "Comment text goes here again";
            $category_id2 = 6;
            $user_id2 = 2;
       
            $testThread2 = new Thread($post2, $category_id2, $user_id2, $post_title, $category);
            $testThread2->save();
       
            //Act
            $result = Thread::find($testThread->getId());
       
            //Assert
            $this->assertEquals($testThread, $result);
        }
       
        function test_addTags()
       {
         $post = "Comment text goes here";
         $category_id = 5;
         $user_id = 4;
         $post_title = "Great Post";
         $category = "PHP";
         
         $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
         $testThread->save();
       
           $tag = "dogs";
           $id = null;
           $new_tag = new Tag($tag, $id);
           $new_tag->save();
       
           $testThread->addTag($new_tag);
       
           $this->assertEquals($testThread->getTags(), [$new_tag]);
       }
       function test_getTags()
       {
           $post = "Comment text goes here";
           $category_id = 5;
           $user_id = 4;
           $post_title = "Great Post";
           $id = 2;
       
           $testThread = new Thread($post, $category_id, $user_id, $post_title, $id);
           $testThread->save();
       
           $tag = "dogs";
           $id = null;
           $new_tag = new Tag($tag, $id);
           $new_tag->save();
       
           $tag2 = "snoop";
           $id2 = null;
           $new_tag2 = new Tag($tag2, $id2);
           $new_tag2->save();
       
           $testThread->addTag($new_tag);
           $testThread->addTag($new_tag2);
           $this->assertEquals($testThread->getTags(), [$new_tag, $new_tag2]);
       }
       
       function test_getUser(){
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
         
         
         $post = "Comment text goes here";
         $category_id = 5;
         $user_id = $testUser->getId();
         $post_title = "Great Post";
         $category = "category";

         $testThread = new Thread($post, $category_id, $user_id, $post_title, $category);
         $testThread->save();
        //  var_dump($testThread);
         
         $result = $testThread->getUser();
         
         $this->assertEquals($result, $username);
         
       }
    }

?>
