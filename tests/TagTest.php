<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Comment.php";
    require_once "src/User.php";
    require_once "src/Tag.php";
    require_once "src/Thread.php";

    $server = 'mysql:host=localhost:8889;dbname=forum_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class TagTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Tag::deleteAll();
        }

        function testGetTag()
        {
            //Arrange
            $tag = "dogs";
            $id = 1;

            $testGetTag = new Tag($tag, $id);

            //Act
            $result = $testGetTag->getId();

            //Assert
            $this->assertEquals($id, $result);

        }

        function testSetTag()
        {
            //Arrange
            $tag = "dogs";
            $id = 1;

            $testSetTag = new Tag($tag, $id);

            //Act
            $testSetTag->setTag("cats");
            $result = $testSetTag->getTag();

            //Assert
            $this->assertEquals("cats", $result);
        }

        function testGetId()
        {
            //Arrange
            $tag = "dogs";
            $id = 1;

            $testGetId = new Tag($tag, $id);

            //Act
            $result = $testGetId->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            //Arrange
            $tag = "dogs";

            $testSave = new Tag($tag);
            $testSave->save();

            //Act
            $result = Tag::getAll();

            //Assert
            $this->assertEquals($testSave, $result[0]);
        }

        function testDeleteTag()
        {
            //Arrange
            $tag = "dogs";

            $testSave = new Tag($tag);
            $testSave->save();

            $tag2 = "cats";

            $testSave2 = new Tag($tag2);
            $testSave2->save();

            //Act
            $testSave2->delete();

            //Assert
            $this->assertEquals([$testSave], Tag::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $tag = "dogs";

            $testSave = new Tag($tag);
            $testSave->save();

            $tag2 = "cats";

            $testSave2 = new Tag($tag2);
            $testSave2->save();

            //Act
            $result = Tag::getAll();

            //Assert
            $this->assertEquals([$testSave, $testSave2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $tag = "dogs";
            $id = 1;

            $testSave = new Tag($tag, $id);
            $testSave->save();

            $tag2 = "cats";
            $id2 = 2;

            $testSave2 = new Tag($tag, $id);
            $testSave2->save();

            //Act
            Tag::deleteAll();

            //Assert
            $result = Tag::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $tag = "dogs";
            $id = 1;

            $testSave = new Tag($tag, $id);
            $testSave->save();

            $tag2 = "cats";
            $id2 = 2;

            $testSave2 = new Tag($tag, $id);
            $testSave2->save();

            //Act
            $result = Tag::find($testSave->getId());

            //Assert
            $this->assertEquals($testSave, $result);
        }
    }

?>
