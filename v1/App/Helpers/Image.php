<?php

/**
 * 
 * Image Function Global
 *
 **/

class ImageHelper extends API{


    // Upload Destination Path
    // =======================================================================
    private $uploadPath         = "./storage/";
    private $uriPath            = "storage/";

    // Image Resolution
    // =======================================================================
    private $thumbnailSize      = 30;

    /**
     *
     *  Generate File Name
     *  
     **/
    private function _generateName ( $name = "" ) {

        return hash( "sha256", date ( "YmdHis" ) . $name );

    }
    

    /**
     *
     *  Upload Image to directory
     *  
     **/
    public function upload ( $input, $path, $size = "full" ) {

        $imgName    = $this->_generateName () . ".png";
        $fileName   = $this->uploadPath . $path . "/" . $imgName;

        if ( isset ( $input["tmp_name"] ) ) {
            $input = $input["tmp_name"];
        }

        $upload     = move_uploaded_file ( $input, $fileName );

        if ( $upload !== false ) {
            return $imgName;
        }

        return $input["error"];
    }

    /**
     *
     *  Upload Many Image to directory
     *  
     **/
    public function uploadMuch ( $input, $path, $name, $size = "full") {

        $fileName   = $this->uploadPath . $path . "/" . $this->_generateName ($name) . ".png";

        $upload     = move_uploaded_file ( $input, $fileName );

        if ( $upload !== false ) {

            return $fileName;
        }

        return $upload;
    }

    
    /**
     *
     *  Get Uploaded Image
     *  
     **/
    public function get ( $path, $fileName, $size = "full", $default = "al" ) {
        
        $fileArray = explode ( ".", $fileName );
        $fileName = $fileArray[0];

        if ( $size != "full" ) {

            $imageSizeProperty = $size . "Size";
            $filePath = $path . "/" . $fileName . "_" . $this->$imageSizeProperty . ".php";
            
            if ( file_exists ( $this->uploadPath . $filePath ) ) {
                
                return $this->uriPath . $filePath;
            }
        }

        $filePath = $path . "/" . $fileName . ".png";

        if ( file_exists ( $this->uploadPath . $filePath ) ) {

            return $this->uriPath . $filePath;
        }

        return $this->uriPath . $path . "/" . $default . ".png";
    }


    /**
     *
     *  Update Uploaded Image
     *  
     **/
    public function update ( $input, $path, $oldName, $size = "full" ) {

        $imageSizeProperty = $size . "Size";

        // $fileResolution = $size != "full" ? "_" . $this->$imageSizeProperty : "";
        $oldFileName    = $this->uploadPath . $path . "/" . $oldName; // . $fileResolution . ".php";
        
        if ( file_exists ( $oldFileName ) ) {

            $upload = $this->upload ( $input, $path, $size );

            if ( $upload !== false ) {

                unlink ( $oldFileName );
            }

            return $upload; 
        }

        return false;
    }

    
    /**
     *
     *  Copy Image to directory
     *  
     **/
    public function copyFromUrl ( $url, $path ) {

        $imageName      = $this->_generateName ();
        $destination    = $this->uploadPath . $path . "/" . $imageName . ".png";

        $getfile    = file_put_contents( $destination, file ( $url ) );

        return $imageName;
    }

}


?>