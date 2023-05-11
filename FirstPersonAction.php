<?php
require_once 'BaseClass.php';

// Démarrer la session
session_start();

// Initialiser la variable d'inventaire à un tableau vide si elle n'existe pas déjà
if (!isset($_SESSION['inventaire'])) {
    $_SESSION['inventaire'] = array();
}

class FirstPersonAction extends BaseClass {

    // Ajouter un nouvel attribut pour stocker l'état de la clé
    private $_cle_ramassee = false;

    public function checkAction() {
        // Récupérer les informations de la position courante sur la carte
        $mapId = $this->getMapId();
        $currentX = $this->getCurrentX();
        $currentY = $this->getCurrentY();
        $currentAngle = $this->getCurrentAngle();

        // Vérifier si une action est possible sur cette carte
        $stmt = $this->_dbh->prepare("SELECT * FROM actions WHERE map_id = :mapId AND status = 0");
        $stmt->bindParam(':mapId', $mapId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //Vérifier si $result contient une valeur
        if ($result) {
            //Vérifier si la clé a été ramassée
            if ($result['item_id'] == 1 && !$this->_cle_ramassee) {
                return true;
            }
            //Vérifier si l'objet requis est dans l'inventaire
            if (in_array($result['requis'], $_SESSION['inventaire'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // La méthode doAction réalise l'action sur la position courante sur la carte
   public function doAction() {
    // Récupérer les informations de la position courante sur la carte
    $mapId = $this->getMapId();
    $currentX = $this->getCurrentX();
    $currentY = $this->getCurrentY();
    $currentAngle = $this->getCurrentAngle();

    // Récupérer l'action à effectuer
    $stmt = $this->_dbh->prepare("SELECT * FROM actions WHERE map_id = :mapId AND status = 0");
    $stmt->bindParam(':mapId', $mapId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la clé doit être ramassée
    if ($result !== false && $result['item_id'] == 1 && !$this->_cle_ramassee) {
        $this->_cle_ramassee = true;
        //Ajouter la clé à l'inventaire
        $_SESSION['inventaire'][] = 'cle';
        // Mettre à jour la description de l'inventaire
        $_SESSION['description'] = implode(', ', $_SESSION['inventaire']);

        // Mettre à jour la base de données pour marquer l'action comme effectuée
        $stmt = $this->_dbh->prepare("UPDATE actions SET status = 1 WHERE id = :id");
        $stmt->bindParam(':id', $result['id']);
        $stmt->execute();
    } elseif ($result !== false && $result['requis'] > 0 && in_array($result['requis'], $_SESSION['inventaire'])) {
        // Vérifier si l'objet requis est dans l'inventaire
        
        // Retirer l'objet requis de l'inventaire
        $key = array_search($result['requis'], $_SESSION['inventaire']);
        unset($_SESSION['inventaire'][$key]);
        // Mettre à jour la description de l'inventaire
        $_SESSION['description'] = implode(', ', $_SESSION['inventaire']);

        // Vérifier si la clé est requise pour ouvrir la porte
        if ($result['item_id'] == 1) {
            // Retirer la clé de l'inventaire
            $key = array_search('cle', $_SESSION['inventaire']);
            unset($_SESSION['inventaire'][$key]);
            // Mettre à jour la description de l'inventaire
            $_SESSION['description'] = implode(', ', $_SESSION['inventaire']);
        }

        // Mettre à jour la base de données pour marquer l'action comme effectuée
        $stmt = $this->_dbh->prepare("UPDATE actions SET status = 1 WHERE id = :id");
        $stmt->bindParam(':id', $result['id']);
        $stmt->execute();
    }
}

public function getStatusAction() {
    // Récupérer les informations de la position courante sur la carte
    $mapId = $this->getMapId();
    $currentX = $this->getCurrentX();
    $currentY = $this->getCurrentY();
    $currentAngle = $this->getCurrentAngle();

    // Vérifier si une action est possible sur cette carte
    $stmt = $this->_dbh->prepare("SELECT * FROM actions WHERE map_id = :mapId AND status = 0");
    $stmt->bindParam(':mapId', $mapId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //Vérifier si $result contient une valeur
    if ($result) {
        // Vérifier si la clé a été ramassée
        if ($result['item_id'] == 1 && in_array('cle', $_SESSION['inventaire'])) {
            // Mettre à jour la description de l'inventaire
            $_SESSION['description'] = implode(', ', $_SESSION['inventaire']);
            
        } }
}
}