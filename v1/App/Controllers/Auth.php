<?php 

    use \Firebase\JWT\JWT;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    // Load Composer's autoloader
    // require '../../../vendor/autoload.php';

    $this->get ('', \Auth::class . ":index");
    $this->get ('/verifyUser/{api_key}', \Auth::class . ":verifyUser");
    $this->post ('/register', \Auth::class . ":RegisterUser");
    $this->get ('/getUsers', \Auth::class . ":getAllUser") -> add(Filter::class);

    $this->post('/login', \Auth::class . ":LoginUser");
    
    class Auth extends API
    {

        public function index ( $request, $response, $args ) {

            // Return feedback data
            return $response->withJSON ( array (
                "status"    => true,
                "message"   => "Hello world"
            ));
    
        }

        public function checkJWT($request, $response, $args)
        {
            # code...
        }

        public function validating($params)
        {
            $this->initModel("auth");
            $stmt = $this->authModel->getEmail(array(
                ":email" => $params
            ));

            if ($stmt != false) {
                return false;
            }
            return true;
        }

        public function LoginUser($request, $response, $args)
        {
            $this->params = $request->getParsedBody();
            
            $this->initModel("auth");
            $this->initModel("secret");

            $stmt = $this->authModel->login(array (
                ":email" => $this->params['email'],
            ));
            
            if ($stmt) {
                // return $response->withJSON(array(
                //     "status" => "200",
                //     "message" => "Hello World!!!",
                //     "data" => $stmt
                // ));
                $decrypt = $this->encryptdecrypt_password("decrypt",$this->params["password"],$stmt["password"]);

                if ($decrypt) {       
                    $token = JWT::encode(array(
                        "data" => $stmt,
                        "time" => date ( "YmdHis" )
                    ),"secretcode",'HS512');

                    $statement = $this->secretModel->addToken(array(
                        ":token" => $token,
                        ":id_user" => $stmt["id_user"] 
                    ));

                    return $response->withJSON(array(
                        "status" => $decrypt,
                        "data" => $stmt,
                        'token' => $token,
                        "message"=>"Login Berhasil!!!"
                    ));
                }
                return $response->withJSON(array('status' => $decrypt));

            }

            return $response->withJSON ( array (
                "status"    => "400",
                "message"   => "Data not found",
            ));


        }   

        public function RegisterUser($request, $response, $args)
        {  
            $this->params = $request->getParsedBody();

            $this->initModel("auth");
            $this->initModel("secret");

            $password = $this->encryptdecrypt_password("encrypt",$this->params["password"],"");
            $api_key = $this->generate_token();

            $valid = $this->validating($this->params['email']);

            if ($valid == true) {
                $register = $this->authModel->register(array(
                    ":name" => $this->params["name"],
                    ":email" => $this->params["email"],
                    ":api_key" => $api_key,
                    ":password" => $password
                ));
    
                $userId = $this->authModel->lastInsertId();
                
                $userSecret = $this->secretModel->insert(array(
                    ":id_user" => $userId,
                    // ":token" => $token,
                    ":api_key" => $api_key
                ));
                $this->sendEmail($this->params["email"],$api_key);
                return $response->withJSON(array(
                    "status" => true,
                    "message" => "Pendaftaran berhasil"
                ));
            }
            return $response->withJSON(array(
                "status" => false,
                "message" => "Email sudah terdaftar"
            ));
        }

        public function sendEmail($email,$api_key)
        {
            $mail = new PHPMailer(true);

            // if ($mail->send()) {
            //     return $response->withJSON(array("message" => "ok"));
            // }
            // return $response->withJSON(array("message" => "gagal"));
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'harlowshaffer@gmail.com'; 
                $mail->Password = '0897harlow';

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587; 
        
                $mail->setFrom('no-reply@Jokeee.com', 'Jokeee Official');
                $mail->addAddress($email);
                $mail->isHTML(false);

                $mail->Subject = 'Verifikasi Akun Anda';
                $mail->Body    = 'Haiii, Segera lakukan aktivasi akun anda ya !!!. Klik link ini http://192.168.0.21/joke/v1/auth/verifyUser/'.$api_key;
                
                if($mail->send()){
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        public function getAllUser($request, $response, $args)
        {
            $this->initModel("auth");

            $statement = $this->authModel->getUser();

            return $response->withJSON ( array (
                "status"    => "200",
                "message"   => "Success fetching data",
                "data"      => $statement
            ));
    
        }

        public function verifyUser($request, $response, $args)
        {
            $this->initModel("auth");
            $this->initModel("secret");
            $this->args = $args;

            $checkApiKey = $this->secretModel->getUserSecretByApiKey(array(
                ":api_key" => $this->args["api_key"]
            ));

            if ($checkApiKey != false) {
                $statement = $this->authModel->verifyUser(array(
                    ":api_key" => $this->args["api_key"]
                ));
                if ($statement) {
                    return $response->withJSON(array(
                        "status" => true,
                        "message" => "Verifikasi berhasil"
                    ));
                }
            }
            else {
                return $response->withJSON(array(
                    "status" => false,
                    "message" => "Illegal Access",
                // "random" => $randomString
                ));
            }
           
            
        }
    }
    

?>