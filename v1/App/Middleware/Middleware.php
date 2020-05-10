<?php

use \Firebase\JWT\JWT;

class Filter extends API { 


    /**
     * 
     * Middleware Invoke Function 
     * 
     */
    public function __invoke ( $request, $response, $next ) {

        if ( ! isset ($request->getHeader ( 'HTTP_AUTHORIZATION' ) [0] ) ) {
            
            return $response->withJSON ( array ( "status" => false, "message" => "Ilegal access!" ) );

        }
        
        try {
            $auth = str_replace ( "Bearer ", "", $request->getHeader ( 'HTTP_AUTHORIZATION' ) [0] );

            $dataToken = JWT::decode($auth,"secretcode",array("HS512"));
            
            $this->initModel("secret");

            $secretData = $this->secretModel->getUserSecret(array(
                ":id_user" => $dataToken->data->id_user,
            ));

            // return $response->withJSON(array("data" => $dataToken));
            
            if ($auth !== $secretData["token"]) {
                return $response->withJSON( array(
                    'status' => false,
                    "message" => "Token missmatch!!!"
                ));
            }

            $request = $request->withAttribute('userId', $dataToken);

            return $next ( $request, $response );
            
        } catch (\Throwable $th) {

           return $response->withJSON(array("status" => "500", "message" => "Access forbidden!!!"));
           
        }
        // $auth = str_replace ( "Bearer ", "", $request->getHeader ( 'HTTP_AUTHORIZATION' ) [0] );

        // list($jwt) = sscanf( $token->toString(), 'Authorization: Bearer %s');

        // $dataToken = JWT::decode($auth,"secretcode",array("HS512"));

        // return $response->withJSON(array("data" => $dataToken));


        // Get token
        // $auth = str_replace ( "Bearer ", "", $request->getHeader ( 'HTTP_AUTHORIZATION' ) [0] );

        // Exploding token
        // $authData = explode("::", $auth);

        // User secret model
        // $this->initModel("secret");

        // Get secret data
        // $secretData = $this->secretModel->getUserSecret(array(
        //     ":id_user" => $authData[0],
        // ));
        // return $response->withJSON( array('data' => $secretData));


        // $tokenAll = explode(":", $secretData["id_user"]);

        // return $response->withJSON(array("data" => $tokenAll));

        // if ( sizeof($secretData) == 0 ) {
        //     return array("status" => false, "message" => "404 not found!");
        // }

        // else if ( ! in_array($authData[1], $tokenAll) ) {
        //     return array("status" => false, "message" => "Token missmatach! ");
        // }

        // $request = $request->withAttribute('userId', $dataToken);


        // return $next ( $request, $response );

    }


    /**
     * 
     * Get Authentication Data
     * 
     */
    // public function getAuth ( $userId, $token ) {

    //     $this->initModel ( "userSecret" );
        
    //     $secretData = $this->userSecretModel->getUserSecret ( array (
    //         ":user_id" => $userId
    //     ));

    //     $tokenAll = explode ( ":", $secretData["token"] );

    //     if ( sizeof ( $secretData ) == 0 ) {
            
    //         return array ( "status" => false, "message" => "404 not found!" );
    //     }

    //     else if ( ! in_array ( $token, $tokenAll ) ) {

    //         return array ( "status" => false, "message" => "Token missmatach!" );
    //     }

    //     return null;
    // }

}