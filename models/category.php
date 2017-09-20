<?php
/**
 * data for categories page /
 * data for categories controller /
 */
class Category extends Model {

    /**
     * select all categories /
     * @return mixed
     */
    public function getCategoryTree()
    {
            $sql = "
    SELECT * 
      FROM category
     ";
        $result = $this->db->query($sql);
        $return = array();
        foreach($result as $val) {
            $return[$val['id_parent']][] = $val;
        }
        return $return;
    }

    /**
     * get from db name of the category by id /
     * @param $id
     * @return mixed
     */
    public function getCategoryName($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM category 
    WHERE category.id = '{$id}' 
      LIMIT 1";
        return $this->db->query($sql);
    }

    /**
     * get from db titles of news for one category by its id /
     * @param $id
     * @return null
     */
    public function getNewsByCategoryId($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM news 
    LEFT JOIN news_category ON news.id = news_category.id_news
      WHERE news_category.id_category = '{$id}'
        ORDER BY news.create_date_time DESC
";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * query for news sorted by categories /
     * @return mixed
     */
    public function getNewsByCategory()
    {
        $sql_categories = "SELECT * FROM categories"; // select all categories
        $categories = $this->db->query($sql_categories);
        $return = array();

        /*
         * for each categori select latest 5 news
         */
        foreach ($categories as $category) {
            $sql = "
SELECT title,create_date_time,news.id AS id,categories.id AS id_category 
  FROM news
    LEFT JOIN news_category ON news.id = news_category.id_news
    LEFT JOIN categories ON news_category.id_category = categories.id
  WHERE categories.id = " . $category['id'] . "
  ORDER BY news.create_date_time DESC
  LIMIT 5
";
            $return = array_merge($return,$this->db->query($sql));
        }

        return $return;
    }

    /**
     * generate data for 2 advertising blocks /
     */
    public function getAdvertising()
    {
        $sql = "SELECT * FROM advertising";
        return $this->db->query($sql);
    }
    

    /**
     * return al parents categories
     * @return mixed
     */
    public function getParentsCategories()
    {
        $sql = "
SELECT * 
  FROM category
    WHERE id_parent = 0";
        return $this->db->query($sql);
    }
    
    public function createCategory($id_parent,$cat_name)
    {
        $id = $this->db->escape($id_parent);
        $name = $this->db->escape($cat_name);
        $sql = "
INSERT INTO category(id_parent,name) 
  VALUES ('{$id}','{$name}')
";
        $this->db->query($sql);
    }

    public function editComment($data = array())
    {
        $id = $this->db->escape($data['id']);
        $text = $this->db->escape($data['text']);

        $approved = (array_key_exists('approved',$data)) ? 1 : 0;

        if ($approved) {
            $sql = "
UPDATE comment SET text='{$text}',approved={$approved} WHERE id='{$id}'
";
            $this->db->query($sql);
        } else {
            $sql = "
UPDATE comment SET text='{$text}' WHERE id='{$id}'
";
            $this->db->query($sql);
        }
    }
    
    public function getPoliticComments() 
    {
        $sql = "
SELECT * FROM comment WHERE approved IS NULL ORDER BY create_date_time
";
        return $this->db->query($sql);
    }
}