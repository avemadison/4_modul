<?php
/**
 * queries for tags /
 */
class Tag extends Model {
    
    /**
     * get whole list of tags /
     * @return mixed
     */
    public function getList()
    {
        $sql = "
SELECT * 
  FROM tag 
    ORDER BY tag.name
";
        return $this->db->query($sql);
    }

    /**
     * get from db name of the tag by id /
     * @param $id
     * @return mixed
     */
    public function getTagName($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM tag
    WHERE tag.id = '{$id}' 
      LIMIT 1";
        return $this->db->query($sql);
    }

    /**
     * get from db titles of news for one tag by its id /
     * ordered by date /
     * @param $id
     * @return null
     */
    public function getNewsByTagId($id)
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM news 
    LEFT JOIN news_tag ON news.id = news_tag.id_news
      WHERE news_tag.id_tag = '{$id}'
        ORDER BY news.create_date_time DESC
";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * generate data for 2 advertising blocks /
     */
    public function getAdvertising()
    {
        $sql = "SELECT * FROM advertising";
        return $this->db->query($sql);
    }

    public function createTag($tag_name)
    {
        $name = $this->db->escape($tag_name);
        $sql = "
INSERT INTO tag (name) 
  VALUES ('{$name}')
";
        $this->db->query($sql);
    }
        
    
    
    
    

    /**
     * TODO correct
     * get one news by tag id/
     * @param $id
     * @return null
     */
    public function getByiD ($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT * FROM news WHERE id = '{$id}'";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    /**
     * TODO correct
     * @param $data
     * @param null $id
     * @return bool
     */
    public function save($data, $id = null)
    {
        if ( !isset($data['title']) || !isset($data['analytical'])) {
            return false;
        }

        $id = (int)$id;
        // shield the sql injections
        $title      = $this->db->escape($data['title']);
        $analytical = isset($data['analytical']);

        if (!$id) { // add new record
            $sql = "
INSERT INTO news
  SET title      = '{$title}',
      analytical = '{$analytical}'
";
        } else { // update existing record
            $sql = "
UPDATE news
  SET title      = '{$title}',
      analytical = '{$analytical}'
";
        }

        return $this->db->query($sql);
    }


}
