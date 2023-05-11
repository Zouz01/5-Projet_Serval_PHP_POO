<?php

class BaseClass { //propriétés de la classe
     protected $_currentX; // Coordonnée X actuelle
    protected $_currentY; // Coordonnée Y actuelle
    protected $_currentAngle; // Orientation actuelle (en degrés)
    protected $_currentMap; // Carte actuelle
    protected $_dbh; // Objet Database pour la connexion à la base de données
    protected $_mapStatus; // Statut de la carte actuelle
    protected $_mapId; // Identifiant de la carte actuelle
   


    //constructeur de la class
    public function __construct() {
            // Connexion à la base de données grâce à la classe Database
            $this->_dbh = new Database('localhost', 'fpview', 'root', 'root');

        
           
    }

     // Méthodes pour modifier et récupérer les propriétés de la classe
    public function setMapId($mapId) {
        $this->_mapId = $mapId;
    }

    public function setCurrentX(int $currentX) {
        $this->_currentX = $currentX;
    }

    public function getCurrentX() {
        return $this->_currentX;
    }

    public function setCurrentY(int $currentY) {
        $this->_currentY = $currentY;
    }

    public function getCurrentY() {
        return $this->_currentY;
    }

  public function setCurrentAngle(int $currentAngle) {
    $this->_currentAngle = $currentAngle;
}

public function getCurrentAngle() {
    return $this->_currentAngle;
}

public function setCurrentMap(string $currentMap) {
    $this->_currentMap = $currentMap;
}

public function getCurrentMap() {
    return $this->_currentMap;
}

public function setMapStatus(int $mapStatus) {
    $this->_mapStatus = $mapStatus;
}

public function setDbh($dbh) {
    $this->_dbh = $dbh;
}
public function getDbh() {
    return $this->_dbh;
}



    // Méthode privée pour vérifier si un déplacement est possible
    private function _checkMove(int $newX, int $newY, int $currentAngle) {
        $stmt = $this->_dbh->prepare("SELECT id FROM map WHERE coordX = :X AND coordY = :Y AND direction = :direction");

        // Vérifie si le déplacement est possible sur la carte actuelle
        //  pour les coordonnées et l'orientation données
        $stmt->bindValue(':X', $newX, PDO::PARAM_INT);
        $stmt->bindValue(':Y', $newY, PDO::PARAM_INT);
        $stmt->bindValue(':direction', $currentAngle, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (!empty($result)) {
            return true;
        } else {
            return false;
            }
        
}



// Méthode privée pour vérifier si un déplacement est possible
public function checkForward() { // Vérifie la possibilité de déplacement vers l’avant
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : 
                    $newX++;
                    break;

                case 90 :
                    $newY++;
                    break;

                case 180 :
                    $newX--;
                    break;

                case 270 :
                    $newY--;
                    break;

                default :
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);

        }
  public function checkBack() { // Vérifie la possibilité de déplacement vers l'arrière
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 :
                    $newX--;
                    break;

                case 90 :
                    $newY--;
                    break;

                case 180 :
                    $newX++;
                    break;

                case 270 :
                    $newY++;
                    break;

                default :
                    break;
            }
             return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }
    public function checkRight() { // Vérifie la possibilité de déplacement vers la droite
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : {
                    $newY--;
                    break;
                }
                case 90 : {
                    $newX++;
                    break;
                }
                case 180 : {
                    $newY++;
                    break;
                }
                case 270 : {
                    $newX--;
                    break;
                }
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }

    public function checkLeft() { // Vérifie la possibilité de déplacement vers la gauche
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : {
                    $newY++;
                    break;
                }
                case 90 : {
                    $newX--;
                    break;
                }
                case 180 : {
                    $newY--;
                    break;
                }
                case 270 : {
                    $newX++;
                    break;
                }
                default : {
                    break;
                }
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }


   public function goForward() { // Effectue le déplacement vers l’avant
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentX++;
                    break;

                case 90 :
                    $this->_currentY++;
                    break;

                case 180 :
                    $this->_currentX--;
                    break;

                case 270 :
                    $this->_currentY--;
                    break;

                default :
                    break;
            }
        }

        public function goBack() { // Effectue le déplacement vers l’arrière
    switch ($this->_currentAngle) {
        case 0 :
            $this->_currentX--;
            break;

        case 90 :
            $this->_currentY--;
            break;

        case 180 :
            $this->_currentX++;
            break;

        case 270 :
            $this->_currentY++;
            break;

        default : 
            break;
    }
}

        public function goRight() { // Effectue le déplacement vers la droite
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentY--;
                    break;

                case 90 :
                    $this->_currentX++;
                    break;

                case 180 :
                    $this->_currentY++;
                    break;

                case 270 :
                    $this->_currentY--;
                    break;

                default :
                    break;
            }
        }

        public function goLeft() { // Effectue le déplacement vers la gauche
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentY++;
                    break;

                case 90 :
                    $this->_currentX--;
                    break;

                case 180 :
                    $this->_currentY--;
                    break;

                case 270 :
                    $this->_currentY++;
                    break;

                default :
                    break;
            }
        }

        public function turnRight() { // Tourne sur la droite
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentAngle = 270;
                    break;

                case 90 :
                    $this->_currentAngle = 0;
                    break;

                case 180 :
                    $this->_currentAngle = 90;
                    break;

                case 270 :
                    $this->_currentAngle = 180;
                    break;

                default :
                    break;
            }
        }

        public function turnLeft() { // Tourne sur la gauche
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentAngle = 90;
                    break;

                case 90 :
                    $this->_currentAngle = 180;
                    break;

                case 180 :
                    $this->_currentAngle = 270;
                    break;

                case 270 :
                    $this->_currentAngle = 0;
                    break;

                default :
                    break;
            }
        }


    public function getMapId() {// Renvoie l'id de map correspondant aux coordonnées X, Y et Angle
    $_currentX = $this->_currentX;
    $_currentY = $this->_currentY;
    $_currentAngle = $this->_currentAngle;

        // Récupérer l'identifiant de la position courante sur la carte à partir de la base de données
        $stmt = $this->_dbh->prepare("SELECT id FROM map WHERE coordX = :X AND coordY = :Y AND direction = :direction");
        $stmt->bindParam(':X', $_currentX);
        $stmt->bindParam(':Y', $_currentY);
        $stmt->bindParam(':direction', $_currentAngle);

        $stmt->execute();
        $result = $stmt->fetch();
        error_log(print_r($result, 1));
        if (isset($result['id'])) {
            return  $result['id'];
        }
    }

  public function getMapStatus(){
    $_mapId = $this->getMapId();

    $stmt = $this->_dbh->prepare("SELECT * FROM actions WHERE map_id = :mapId");
    $stmt -> bindParam(':mapId',$_mapId,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();

    if (is_array($result)) {
        $this->_mapStatus = $result['status'];
        return $this->_mapStatus;
        } else {
        // Gérer le cas où la requête ne renvoie pas de résultat
        }
    }
   public function RazAction() {
 
  // Mettre tous les statuts des points de la carte à 0
  $stmt = $this->_dbh->prepare("UPDATE actions SET status = 0");
  $stmt->execute();
}
}
   
?>