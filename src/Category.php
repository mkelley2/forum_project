<?php
    class Category
    {
        private $category;
        private $id;

        function __construct($category, $id = null)
        {
            $this->category = $category;
            $this->id = $id;
        }

        function setCategory($new_category)
        {
            $this->category = (string) $new_category;
        }

        function getCategory()
        {
            return $this->category;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO categories (category) VALUES ('{$this->getCategory()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $category) {
                $category_text = $category['category'];
                $id = $category['category_id'];
                $new_category = new Category($category_text, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category) {
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                  $found_category = $category;
                }
            }
            return $found_category;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE category_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM threads WHERE category_id = {$this->getId()};");
        }
    }
?>
