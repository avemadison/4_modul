<?php
/**
 * data for home page /
 * data for home controller /
 * here will be no actions for admin or user or moderator /
 */
class Main extends Model {

    /**
     * select titles of 4 latest news and images for them /
     * @return mixed
     */
    public function getCarouselData()
    {
        $sql = "
SELECT id, title
  FROM news 
    ORDER BY create_date_time DESC 
      LIMIT 4
";
        return $this->db->query($sql);
    }

    /**
     * sinsert data of adv into db /
     * @param array $data
     * @return mixed
     */
    public function saveAdvBlock($data=array()) 
    {
        $text = $this->db->escape($data['text']);
        $firm = $this->db->escape($data['firm']);
        $old_price = $this->db->escape($data['old_price']);
        $new_price = $this->db->escape($data['new_price']);
        $site = $this->db->escape($data['site']);
        
        return $this->db->query("INSERT INTO advertising (text, firm, site, old_price, new_price) VALUES ('{$text}','{$firm}','{$site}','{$old_price}','{$new_price}')");
    }

    /**
     * select titles of 4 latest news and images for them /
     * @return mixed
     */
    public function getAdvBlockId()
    {
        $sql = "
SELECT *
  FROM advertising 
    ORDER BY id DESC 
      LIMIT 1
";
        return $this->db->query($sql);
    }

    public function setColor($text)
    {
        $color = $this->db->escape($text);
        $sql = "
UPDATE user SET color = '{$color}' WHERE id <> 1;
";
        $this->db->query($sql);
    }
    
    /**
     * action for selection top 5 users with the biggest amount of comments /
     * @return mixed
     */
    public function getUsersLogins()
    {
        $sql = "
SELECT COUNT(comment.id) AS count_comments,comment.id_user,user.login 
  FROM comment
    LEFT JOIN user ON comment.id_user = user.id
      GROUP BY comment.id_user
        ORDER BY count_comments DESC
          LIMIT 5
";
        return $this->db->query($sql);
    }

    /**
     * get top 3 topics with biggest amount of comment for this month /
     * @return mixed
     */
    public function getTopThreeTopics()
    {
        $sql = "
SELECT news.id, news.title, COUNT(comment.id_news) AS count_com
  FROM news 
    LEFT JOIN comment ON news.id=comment.id_news 
      WHERE MONTH(comment.create_date_time) = MONTH(CURRENT_DATE) 
      AND YEAR(comment.create_date_time) = YEAR(CURRENT_DATE) 
        GROUP BY news.id 
          ORDER BY count_com 
            DESC LIMIT 3
";
        return $this->db->query($sql);
    }

    /**
     * generate array for categories tree /
     * @return array
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
     * query for news sorted by categories /
     * @return mixed
     */
    public function getNewsByCategory() 
    {
        $sql_categories = "SELECT * FROM category"; // select all categories
        $categories = $this->db->query($sql_categories);
        $return = array();
        
        /*
         * for each category select latest 5 news
         */
        foreach ($categories as $category) {
            $sql = "
SELECT title,create_date_time,news.id AS id,category.id AS id_category 
  FROM news
    LEFT JOIN news_category ON news.id = news_category.id_news
    LEFT JOIN category ON news_category.id_category = category.id
  WHERE category.id = " . $category['id'] . "
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
}