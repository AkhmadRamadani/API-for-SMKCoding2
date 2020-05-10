<?php

class API {

    public $args   = array();
    public $params = array();



    public function __construct () { 

        global $config;

        $database = new Database ( $config["db"] );

        $this->db = $database->get ();
    }
    
    /**
     *
     *  Model Initialization
     *  
     **/
    public function initModel ( $model ) {

        $modelName = $model . "Model";

        require_once ( API_BASE . "App/Models/" . $model . ".php" ); // Main System

        $this->$modelName = new $modelName ();
    }

    
    /**
     *
     *  Helper Initialization
     *  
     **/
    public function initHelper( $helper ) {

        $helper = strtolower ( $helper );
        $helper_name = $helper . "Helper";

        $helperObject = ucfirst ( $helper ) . "Helper";

        require_once ( API_BASE . "App/Helpers/" . ucfirst ( $helper ) . ".php" ); // Main System

        $this->$helper_name = new $helperObject ();
    }

    
    /**
     *
     *  Sibling Controller Initialization
     *  
     **/
    public function initSiblingController ( $controller ) {

        $controller_name = $controller . "Controller";

        $controller = ucfirst ( $controller );
        
        require_once ( API_BASE . "App/Controllers/" . $controller . ".php" ); // Main System

        $this->$controller_name = new $controller ();
    }


    /**
     *
     *  Controller Initialization
     *  
     **/
     public function initController ( $controller ) {
        
        $this->initSiblingController ( $controller );
    }


    /**
     * 
     * Get Authorization Data
     * 
     */
    public function setAuth ( $request ) {

        $authData = str_replace ( "Bearer ", "", $request->getHeader ( 'HTTP_AUTHORIZATION' ) [0] );

        $this->auth = json_decode ( $authData );
    }


    /**
     *
     *  Password Encryption
     *  
     **/
    public function encryptdecrypt_password( $action,$password,$params ) {
        
        if ($action == "encrypt") {
            if ( isset( $password ) ) {

                return password_hash(md5("thisissecretkey".$password."09876!@#$%"),PASSWORD_BCRYPT);
            }
        }
        else if ($action == "decrypt") {
            if ( isset( $password ) ) {

                return password_verify(md5("thisissecretkey".$password."09876!@#$%"),$params);
            }
        }
    }
    /**
     *
     *  User Token Generate
     *  
     **/
    public function generate_token() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 

        for ($i = 0; $i < 30; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
    
        return md5 ( "!@#$%" . date("dmyHis") . $randomString . "0987" );
    }

    public function generateKode($params, $id, $last)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = '';
        $totalWord = str_word_count($params);
        $hasilKode = "";
        

        for ($i = 0; $i < 3; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 


        if ($totalWord > 1) {
            $inisial = explode(" ", $params);
            $acronym = " ";
            foreach($inisial as $i){
                $acronym .= $i[0];
            }

            $hasilKode = strtoupper($acronym).$id."-".sprintf("%04s", $last);
        }else{
            $hasilKode = strtoupper($params[0]).$id."-".$last;
        }
        return $hasilKode;
    }


    /**
     *
     *  API Current Version
     *  
     **/
    public function versions( $request, $response, $args ) {

        return $response->withJSON( array (
            "version_code"  => 1,
            "content"       => "Version 1.0",
            "forceUpdate"   => false
        ));  
    }

}