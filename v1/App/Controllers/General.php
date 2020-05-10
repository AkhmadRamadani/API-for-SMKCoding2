<?php

$this->post ('/post', \General::class . ":Post");
$this->post ('/deletePost', \General::class . ":DeletePost");
$this->post ('/deleteKomen', \General::class . ":DeleteKomentar");
$this->post ('/postKomen', \General::class . ":PostKomentar");

class General extends API {

    public function Post($request, $response, $args)
    {
        $this->params = $request->getParsedBody();

        $this->initModel("general");
        $this->initHelper("Image");

        $getFile = $_FILES['img']['name'];
        $file = $_FILES['img'];

        $uploadImg = $this->imageHelper->upload($file,'imagePost');

        $post = $this->generalModel->post(array(
            ":id_user"=> $this->params["id_user"],
            ":text"=> $this->params["text"],
            ":img"=> $uploadImg,
        ));

        if ($uploadImg) {
            return $response->withJSON(array(
                "status" => 200,
                "data" => $post
            ));
        }
        else {
            return $response->withJSON(array(
                "status" => false,
                "message" => 'gagal post'
            ));
        }
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

}
?>
