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


    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Category::deleteAll();
        }

        function testGetCategory()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testGetCategory = new Category($category, $id);

            //Act
            $result = $testGetCategory->getId();

            //Assert
            $this->assertEquals($id, $result);

        }

        function testSetCategory()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testSetCategory = new Category($category, $id);

            //Act
            $testSetCategory->setCategory("cats");
            $result = $testSetCategory->getCategory();

            //Assert
            $this->assertEquals("cats", $result);
        }

        function testGetId()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testGetId = new Category($category, $id);

            //Act
            $result = $testGetId->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function testSave()
        {
            //Arrange
            $category = "dogs";

            $testSave = new Category($category);
            $testSave->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals($testSave, $result[0]);
        }

        function testDeleteCategory()
        {
            //Arrange
            $category = "dogs";

            $testSave = new Category($category);
            $testSave->save();

            $category2 = "cats";

            $testSave2 = new Category($category2);
            $testSave2->save();

            //Act
            $testSave2->delete();

            //Assert
            $this->assertEquals([$testSave], Category::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testSave = new Category($category, $id);
            $testSave->save();

            $category2 = "cats";
            $id2 = 2;

            $testSave2 = new Category($category, $id);
            $testSave2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$testSave, $testSave2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testSave = new Category($category, $id);
            $testSave->save();

            $category2 = "cats";
            $id2 = 2;

            $testSave2 = new Category($category, $id);
            $testSave2->save();

            //Act
            Category::deleteAll();

            //Assert
            $result = Category::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $category = "dogs";
            $id = 1;

            $testSave = new Category($category, $id);
            $testSave->save();

            $category2 = "cats";
            $id2 = 2;

            $testSave2 = new Category($category, $id);
            $testSave2->save();

            //Act
            $result = Category::find($testSave->getId());

            //Assert
            $this->assertEquals($testSave, $result);
        }
    }

?>
