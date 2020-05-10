<?php 

    class authModel extends API
    {
        public function lastInsertId() {

            return $this->db->lastInsertId();
    
        }
        public function login($params)
        {
            $query = $this->db->prepare("
                SELECT * FROM `user` WHERE :email = email
            ");

            $query->execute($params);

            return $query->fetch(PDO::FETCH_ASSOC);

        }
        public function register($params)
        {
            $register = $this->db->prepare("
                INSERT INTO user VALUES(NULL, :name, :email, :password, 0, :api_key)
            ");

            return $register->execute($params);
        }

        public function getUser()
        {
            $get = $this->db->prepare("
                SELECT * FROM user 
            ");

            $get->execute();

            return $get->fetchAll(PDO::FETCH_ASSOC);

        }

        public function getEmail($params)
        {
            $get = $this->db->prepare("
                SELECT email FROM user WHERE email = :email 
            ");

            $get->execute($params);

            return $get->fetch(PDO::FETCH_ASSOC);

        }

        public function verifyUser($params)
        {
            $update = $this->db->prepare("
                UPDATE user SET verified = 1 WHERE api_key = :api_key
            ");
            
            return $update->execute($params);
        }
    }

?>