<?php 

    class secretModel extends API 
    {
        public function insert($params)
        {
            $insert = $this->db->prepare("
                INSERT INTO user_secret VALUES(NULL, :id_user, '', :api_key)
            ");

            return $insert->execute($params);
        }
        public function getUserSecret($params)
        {
            $userSecret = $this->db->prepare("
                SELECT * FROM user_secret WHERE id_user = :id_user
            ");

            $userSecret->execute($params);

            return $userSecret->fetch(PDO::FETCH_ASSOC);
        }
        public function getUserSecretByApiKey($params)
        {
            $userSecret = $this->db->prepare("
                SELECT * FROM user_secret WHERE api_key = :api_key
            ");

            $userSecret->execute($params);

            return $userSecret->fetch(PDO::FETCH_ASSOC);
        }
        public function addToken($params)
        {
            $addToken = $this->db->prepare("
                UPDATE user_secret SET token = :token WHERE id_user = :id_user
            ");
            return $addToken->execute($params);
        }
    }
    

?>