<?php
/**
 *
 */
class Message extends Model {

    public function save($data, $id = null)
    {
        if ( !isset($data['name']) || !isset($data['email']) || !isset($data['message'])) {
            return false;
        }
        
        $id = (int)$id;
        // shield the sql injections
        $name     = $this->db->escape($data['name']);
        $email    = $this->db->escape($data['email']);
        $message = $this->db->escape($data['message']);
        
        if (!$id) { // add new record
            $sql = "
INSERT INTO messages
  SET name     = '{$name}',
      email    = '{$email}',
      message = '{$message}'
";
        } else { // update existing record
            $sql = "
UPDATE messages
  SET name     = '{$name}',
      email    = '{$email}',
      password = '{$message}',
      WHERE id = '{$id}'
";
        }
        
        return $this->db->query($sql);
    }
    
    public function getList() 
    {
        $sql = "SELECT * FROM messages";
        return $this->db->query($sql);
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