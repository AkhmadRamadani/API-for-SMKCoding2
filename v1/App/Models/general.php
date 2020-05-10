<?php

    class generalModel extends API
    {
        public function postWithImage($params)
        {
            $post = $this->db->prepare("
                INSERT INTO post 
                VALUES (NULL, :text, :img, :id_user)
            ");

            return $post->execute($params);
        }
        public function post($params)
        {
            $post = $this->db->prepare("
                INSERT INTO post (text, id_user)
                VALUES (:text, :id_user)
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
        public function getAllPostLatest($params)
        {
            $getPost = $this->db->prepare("
            select *, 
                (select count(komentar.id_komentar) from komentar where komentar.id_post=post.id_post) as totalKomen,
                (select count(sukai.id_suka) from sukai where sukai.id_post = post.id_post) as totalLike, 
                (select count(sukai.id_suka) from sukai where sukai.id_post=post.id_post AND sukai.id_user = :id_user) as isLiked
            from post ORDER BY post.id_post DESC
            ");

            $getPost->execute($params);

            return $getPost->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getAllPostPopular($params)
        {
            $getPost = $this->db->prepare("
            select *, 
                (select count(komentar.id_komentar) from komentar where komentar.id_post=post.id_post) as totalKomen,
                (select count(sukai.id_suka) from sukai where sukai.id_post = post.id_post) as totalLike, 
                (select count(sukai.id_suka) from sukai where sukai.id_post=post.id_post AND sukai.id_user = :id_user) as isLiked
            from post ORDER BY totalLike DESC
            ");

            $getPost->execute($params);

            return $getPost->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>