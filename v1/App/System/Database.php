<?php

class Database {

    public $pdo;

    public function __construct ( $settings ) {

        $this->make ( $settings );
    }


    public function make ( $settings ) {

        $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'], $settings['user'], $settings['pass']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        $this->pdo = $pdo;
    }


    public function get () {

        return $this->pdo;
    }
}