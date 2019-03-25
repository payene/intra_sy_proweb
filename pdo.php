<?php
// $host_name = 'localhost';
// $database = 'db534334386';
// $user_name = 'dbo534334386';
// $password = 'msqlpxcv&@dfHF95ds0';

// $dbh = null;
// try {
//   $dbh = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
// } catch (PDOException $e) {
//   echo "Erreur!: " . $e->getMessage() . "<br/>";
//   die();
// }
?>

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pdo
 *
 * @author programmer
 */
define('DB_HOST', 'db553160573.db.1and1.com');
define('DB_USR', 'dbo553160573');
define('DB_PWD', 'onlineproweb');
define('DB_NAME', 'db553160573');

# --------------------------------------------------------------------------
# Connexion a la base de données
# --------------------------------------------------------------------------

class PdoConnection {

    //put your code here
    # --------------------------------------------------------------------------
    # Initialisation de l'instance
    # --------------------------------------------------------------------------
    private static $instance;

    # --------------------------------------------------------------------------
    # Création d'un objet PDO ou renvoi de l'objet existant
    # --------------------------------------------------------------------------

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USR, DB_PWD);
            self::$instance->query("SET NAMES 'utf8'");
        }
        return self::$instance;
    }

}

$state = PdoConnection::getInstance();
var_dump($state);