<?php

    class generalModel extends API
    {
        public function post($params)
        {
            $post = $this->db->prepare("
                INSERT INTO post 
                VALUES (NULL, :text, :img, :id_user)
            ");

            return $post->execute($params);
        }
        public function deletePost($params)
        {
            $delete = $this->db->prepare("
                DELETE FROM post WHERE id_post = :id_post
            ");

            return $delete->execute($params);
        }
        public function postKomen($params)
        {
            $komen = $this->db->prepare("
                INSERT INTO komentar 
                VALUES (NULL, :id_post, :id_user, :komentar)
            ");

            return $komen->execute($params);
        }
        public function deleteKomen($params)
        {
            $delete = $this->db->prepare("
                DELETE FROM komentar WHERE id_komentar = :id_komentar
            ");

            return $delete->execute($params);
        }
    }
?>