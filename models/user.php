<?php
/**
 * model for Users controller /
 */
class User extends Model {

    /**
     * registry 1 user /
     * @param $first_name
     * @param $last_name
     * @param $login
     * @param $email
     * @param $password
     * @param $date
     * @return mixed
     */
    public function registerUser($first_name, $last_name, $login, $email, $password, $date)
    {
        $first_name = $this->db->escape($first_name);
        $last_name  = $this->db->escape($last_name);
        $login      = $this->db->escape($login);
        $email      = $this->db->escape($email);
        $password   = $this->db->escape($password);
        $sql = "
INSERT INTO user (first_name, last_name, login, email, password, date_of_birth) 
  VALUES ('{$first_name}', '{$last_name}', '{$login}', '{$email}', '{$password}', '{$date}')
";
        return $this->db->query($sql);
    }

    /**
     * get user from table by login /
     * @param $login
     * @return bool or 1 result with 1 user
     */
    public function getByLogin($login)
    {
        $login = $this->db->escape($login);
        $sql = "
SELECT * 
  FROM user 
    WHERE login = '{$login}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * get user from table by email /
     * @param $email
     * @return bool or 1 result with 1 user
     */
    public function getByEmail($email)
    {
        $email = $this->db->escape($email);
        $sql = "
SELECT * 
  FROM user 
    WHERE email = '{$email}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }
    
    public function getUsersList() 
    {
        $sql= "
SELECT * 
  FROM user";
        return $this->db->query($sql);
    }

    /**
     * get user data by his id /
     * @param $id
     * @return bool
     */
    public function getUserById($id) 
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT * 
  FROM user 
    WHERE user.id = '{$id}'
";
        $result = $this->db->query($sql);
        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * get all user comment by date /
     * @param $id
     * @return bool
     */
    public function getUserComments($id) 
    {
        $id = $this->db->escape($id);
        $sql = "
SELECT *
  FROM user
    JOIN comment on user.id = comment.id_user
      WHERE comment.text <> '' AND user.id = '{$id}'
        ORDER BY comment.create_date_time
";
        return $this->db->query($sql);
    }

    /**
     * data for advertising blocks /
     * @return mixed
     */
    public function getAdvertising()
    {
        $sql = "SELECT * FROM advertising";
        return $this->db->query($sql);
    }
}