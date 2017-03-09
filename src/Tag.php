<?php
    Class Tag
    {
        private $tag;
        private $id;

        function __construct($tag, $id = null)
        {
            $this->tag = $tag;
            $this->id = $id;
        }

        function getTag()
        {
            return $this->tag;
        }

        function setTag($tag)
        {
            $this->tag = $tag;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
          
            $all_tags = Tag::getAll();
            foreach ($all_tags as $tag) {
                if(strtolower($tag->getTag()) == strtolower($this->tag)){
                    return false;
                }
            }
            $GLOBALS['DB']->exec("INSERT INTO tags (tag) VALUES ('{$this->getTag()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
            
            return true;
        }

        static function getAll()
        {
            $returned_tags = $GLOBALS['DB']->query("SELECT * FROM tags;");
            $tags = array();
            foreach($returned_tags as $tag) {
                $tag_text = $tag['tag'];
                $tag_id = $tag['tag_id'];
                $new_tag = new Tag($tag_text, $tag_id);
                array_push($tags, $new_tag);
            }
            return $tags;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM tags;");
          $GLOBALS['DB']->exec("DELETE FROM comments_tags");
          $GLOBALS['DB']->exec("DELETE FROM threads_tags");
        }

        static function find($search_id)
        {
            $found_tag = null;
            $tags = Tag::getAll();
            foreach($tags as $tag) {
                $tag_id = $tag->getId();
                if ($tag_id == $search_id) {
                  $found_tag = $tag;
                }
            }
            return $found_tag;
        }

        static function findByName($search_id)
        {
            $found_tag = null;
            $tags = Tag::getAll();
            foreach($tags as $tag) {
                $tag_id = $tag->getTag();
                if ($tag_id == $search_id) {
                  $found_tag = $tag;
                }
            }
            return $found_tag;
        }


        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM tags WHERE tag_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM threads_tags WHERE tag_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM comments_tags WHERE tag_id = {$this->getId()};");
        }
    }

?>
