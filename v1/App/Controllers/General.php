<?php

$this->post ('/post', \General::class . ":Post");
$this->post ('/deletePost', \General::class . ":DeletePost");
$this->post ('/deleteKomen', \General::class . ":DeleteKomentar");
$this->post ('/postKomen', \General::class . ":PostKomentar");
$this->post ('/getPostDataLatest', \General::class . ":GetAllPostLatest");
$this->post ('/getPostDataPopular', \General::class . ":GetAllPostPopular");
$this->post ('/getPostDataById', \General::class . ":GetPostDataById");
$this->post ('/getKomentarById', \General::class . ":GetKomentarbyId");
$this->post ('/search', \General::class . ":Search");

class General extends API {

    public function Post($request, $response, $args)
    {
        $this->params = $request->getParsedBody();

        $this->initModel("general");
        $this->initHelper("Image");


        // return $response->withJSON(array(
        //     "status" => 200,
        //     "message" => $file
        // ));

        
        $file = $_FILES['img'];
        // if ($file != null) {
            $uploadImg = $this->imageHelper->upload($file,'imagePost');

            // $post = $this->generalModel->postWithImage(array(
            //     ":id_user"=> $this->params["id_user"],
            //     ":text"=> $this->params["text"],
            //     ":img"=> $uploadImg,
            // ));
            // return $response->withJSON(array(
            //     "status" => 200,
            //     "data" => $uploadImg
            // ));
            if ($uploadImg == false) {
                $post = $this->generalModel->post(array(
                    ":id_user"=> $this->params["id_user"],
                    ":text"=> $this->params["text"], 
                ));
                return $response->withJSON(array(
                    "status" => 200,
                    "data" => $post
                ));
            }
            else {
                $post = $this->generalModel->postWithImage(array(
                    ":id_user"=> $this->params["id_user"],
                    ":text"=> $this->params["text"],
                    ":img"=> $uploadImg,
                ));
                return $response->withJSON(array(
                    "status" => 200,
                    "data" => $post
                ));
            }
            return $response->withJSON(array(
                    "status" => false,
                    "message" => 'gagal post'
                ));
        // }
        // else{
        //     $post = $this->generalModel->post(array(
        //         ":id_user"=> $this->params["id_user"],
        //         ":text"=> $this->params["text"]
        //     ));
        //     if ($post) {
        //         return $response->withJSON(array(
        //             "status" => 200,
        //             "message" => "Berhasil"
        //         ));
        //     }
        //     else {
        //         return $response->withJSON(array(
        //             "status" => false,
        //             "message" => 'gagal post'
        //         ));
        //     }
        // }

        
    }

    public function DeletePost($request, $response, $args)
    {
        $this->params = $request->getParsedBody();

        $this->initModel("general");

        $deletePost = $this->generalModel->deletePost(array(
            ":id_post" => $this->params['id_post']
        ));

        if ($deletePost) {
            return $response->withJSON(array(
                "status" => 200,
                "message" => "Berhasil menghapus data"
            ));
        }
        else {
            return $response->withJSON(array(
                "status" => false,
                "message" => 'gagal menghapus data'
            ));
        }
    }

    public function PostKomentar($request, $response, $args)
    {
        $this->params = $request->getParsedBody();

        $this->initModel("general");

        $komenPost = $this->generalModel->postKomen(array(
            ":id_post" => $this->params['id_post'],
            ":id_user" => $this->params['id_user'],
            ":komentar" => $this->params['komentar']
        ));

        if ($komenPost) {
            return $response->withJSON(array(
                "status" => 200,
                "message" => "Berhasil mengirim komentar"
            ));
        }
        else {
            return $response->withJSON(array(
                "status" => false,
                "message" => 'gagal mengirim komentar'
            ));
        }
    }

    public function DeleteKomentar($request, $response, $args)
    {
        $this->params = $request->getParsedBody();

        $this->initModel("general");

        $deletePost = $this->generalModel->deleteKomen(array(
            ":id_komentar" => $this->params['id_komentar']
        ));

        if ($deletePost) {
            return $response->withJSON(array(
                "status" => 200,
                "message" => "Berhasil menghapus komen"
            ));
        }
        else {
            return $response->withJSON(array(
                "status" => false,
                "message" => 'gagal menghapus komen'
            ));
        }
    }

    public function GetAllPostLatest($request, $response, $args)
    {
        $this->params = $request->getParsedBody();
        $this->initModel("general");

        $stmt = $this->generalModel->getAllPostLatest(array (
            ":id_user" => $this->params["id_user"],
        ));
        if ($stmt) {
            return $response->withJSON(array(
                "status" => "200",
                "data" => $stmt
            ));
        }else{
            return $response->withJSON(array(
                "status" => "400",
                "message" => "error"
            ));
        }
    }

    public function GetAllPostPopular($request, $response, $args)
    {
        $this->params = $request->getParsedBody();
        $this->initModel("general");

        $stmt = $this->generalModel->getAllPostPopular(array (
            ":id_user" => $this->params['id_user'],
        ));
        if ($stmt) {
            return $response->withJSON(array(
                "status" => "200",
                "data" => $stmt
            ));
        }else{
            return $response->withJSON(array(
                "status" => "400",
                "message" => "error"
            ));
        }
    }

    public function GetPostDataById($request, $response, $args)
    {
        $this->params = $request->getParsedBody();
        $this->initModel("general");

        $stmt = $this->generalModel->getPostDatabyId(array (
            ":id_user" => $this->params['id_user'],
            ":id_post" => $this->params['id_post']
        ));

        $komentar = $this->GetKomentarById($this->params['id_post']);

        if ($stmt) {
            return $response->withJSON(array(
                "status" => "200",
                "dataPost" => $stmt,
                "komentar" => $komentar
            ));
        }else{
            return $response->withJSON(array(
                "status" => "400",
                "message" => "error"
            ));
        }
    }

    public function GetKomentarById($params)
    {
        $this->initModel("general");

        $stmt = $this->generalModel->getKomentarbyId(array (
            ":id_post" => $params
        ));

        return $stmt;
        
    }

    public function Search($request , $response , $args)
    {
        $this->params = $request->getParsedBody();
        $keyword = $this->params["keyword"];

        $selectJoke = $this->SearchJoke($keyword,$this->params['id_user']);
        $selectUser = $this->SearchNama($keyword);

        return $response->withJSON(array(
            "user" => $selectUser,
            "joke" => $selectJoke
        ));

    }
    public function SearchJoke($params,$params2)
    {
        $this->initModel('general');

        $select = $this->generalModel->searchJokeText(array(
            ':text' => $params,
            ":id_user" => $params2
        ));
        
        return $select;
        
    }
    public function SearchNama($params)
    {
        $this->initModel('general');
        $select = $this->generalModel->searchUser(array(
            ':nama' => $params
        ));
        
        return $select;
    }

}
?>
