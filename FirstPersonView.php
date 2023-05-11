<?php
// require_once('baseclass.php');

// Définition de la classe FirstPersonView, qui hérite de BaseClass
class FirstPersonView extends BaseClass {

    // Déclaration des propriétés de la classe
    protected $_viewportWidth;
    protected $_viewportHeight;
    protected $_cellWidth;
    protected $_cellHeight;
    protected $_canvasWidth;
    protected $_canvasHeight;    
    protected  $_mapId;
  
    // Fonction pour récupérer l'image correspondant à la carte
    public function getView(BaseClass $bp) {
        // Récupération de l'id de la carte à afficher
        $mapId = $bp->getMapId();
        
        // Requête pour récupérer les informations de l'image correspondant à la carte
        $stmt = $bp->_dbh->prepare("SELECT * FROM images WHERE map_id=:id");
        $stmt->bindParam(':id', $mapId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si aucune image n'est trouvée, retourner une chaîne vide
        if (!$result) {
            return "";
        }
        
        // Construction du chemin d'accès à l'image
        $imagePath = "images/" . $result['path'];
        return $imagePath = "images/" . $result['path'];
    }
    
    public function getAnimCompass(BaseClass $bp) {
  // Calcul de l'angle actuel de la boussole modulo 360
  $_currentAngle = $bp->getCurrentAngle() % 360;

  // Retourner la classe CSS correspondant à l'angle actuel de la boussole
  switch ($_currentAngle) {
    case 0:
      return "e";
    case 90:
      return "n";
    case 180:
      return "w";
    case 270:
      return "s";
  }

}
}