<?php


/**
* 
* Notification Helper Class
*
**/

class NotificationHelper extends API {
    
    
    private $userId     = 0;
    private $objectId   = array ();
    private $sourceId	= 0;
    private $roomId     = 0;
	private $deviceId 	= 0;
	private $title		= "API";
	private $message 	= "";
    private $kind		= 0;
    private $subKind    = 0;
	private $metadata	= array ();
    private $priority	= "high";
    
    /**
     *
     *  Push Notification for Public
     *  
     **/
    public function _getSourceData ( $subjectId ) {

        $this->initModel ( "users" );

        return $this->usersModel->get_users ( array (
            ":user_id"  => $subjectId,
            ":email"    => "",
            ":username" => ""
        ));
        
    }


    /**
     *
     *  Push Notification for Public
     *  
     **/
    public function push ( $params ) {

        $this->initModel ( "userSecret" );

        $subjectData = $this->_getSourceData ( $params["userId"] );

        $this->userId   = $params["userId"];
        $this->message  = $subjectData["fullName"] . $params["message"];
        $this->kind     = $params["kind"];
        $this->subKind  = $params["subKind"];
        $this->objectId = $params["objectId"];
        $this->sourceId = $params["sourceId"];
        $this->roomId   = $params["roomId"];
        $this->metadata = $params["metadata"];


        if ( is_array ( $this->objectId ) ) {

            $this->_pushLoop ();
        } 

        else {

            if ( $this->userId != $this->objectId ) {
        
                $userSecret = $this->userSecretModel->getUserSecret ( array (
                    ":user_id"  => $this->objectId
                ));

                $this->deviceId = $userSecret["signal_id"];

                $this->_push();

                $this->_insertToDB ( $this->objectId );
            }
        }
    }


    /**
     *
     *  Loop for each user will accept notification
     *  
     **/
    public function _pushLoop ( $index = 0 ) {

        if ( $this->userId != $this->objectId[$index]["userId"] ) {

            $userSecret = $this->userSecretModel->getUserSecret ( array (
                ":user_id"  => $this->objectId[$index]["userId"]
            ));
    
            $this->deviceId = $userSecret["signal_id"];
    
            $this->_push ();

            $this->_insertToDB ( $this->objectId[$index]["userId"] );
        }

        if ( $index < sizeof ( $this->objectId ) - 1 ) $this->_pushLoop ( $index + 1 );
    }


	/**
     *
     *  Push Notification
     *  
     **/
    private function _push () {
    
        // Fields of Notification Content
        $fields = array (
            'to'       => $this->deviceId,
            'priority' => $this->priority,
            'data'     => array (
                'title'     => $this->title,
                'message'   => $this->message,
                'kind'      => $this->kind,
                'subKind'   => $this->subKind,
                'sourceId'  => $this->sourceId,
                'loungeId'  => $this->roomId,
                'metadata'  => $this->metadata
            )
        );

        // echo "\nFields : "; print_r($fields);

        // Header of Notification Process
        //$headers = array (
        //    self::GOOGLE_FCM_URL,
        //    'Content-Type: application/json',
        //    'Authorization: key=' . self::GOOGLE_API_KEY
        //);

        $result = "";

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, self::GOOGLE_FCM_URL);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // $result = curl_exec($ch);
        
        // if ( $result === FALSE ) {
        //     die ( 'Problem occurred: ' . curl_error ( $ch ) );
        // }

        // curl_close ( $ch );

        // echo "\nResult : "; print_r($result);

        return json_decode ( $result );
    }


    /**
     *
     *  Insert into notification database
     *  
     **/
    private function _insertToDB ( $objectId ) {

        $this->initModel ( "notifications" );

        $insert = $this->notificationsModel->insert ( array (
            ":user_id"      => $this->userId,
            ":obj_id"       => $objectId,
            ":source_id"    => $this->sourceId,
            ":room_id"      => $this->roomId,
            ":date"         => date("Y:m:d"),
            ":time"         => date("H:i:s"),
            ":kind"         => $this->kind,
            ":sub_kind"     => $this->subKind,
            ":message"      => $this->message
        ));
    }

}

?>