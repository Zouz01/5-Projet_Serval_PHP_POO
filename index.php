<?php
// Autoload pour charger les classes
spl_autoload_register(function ($class_name) {
  $file = $class_name . ".php";
  if (file_exists($file)) {
    require_once $file;
  }
});

// Instanciez les classes et initialisez la position et l'angle de départ
$bp = new BaseClass();
$fpv = new FirstPersonView();
$fpt = new FirstPersonText();
$action = new FirstPersonAction($bp->getDbh());


// Mettre à jour la position et l'angle actuels si le formulaire est soumis (setters)
if (isset($_POST['currentAngle'])) {
  $bp->setCurrentX(intval($_POST['currentX']));
  $bp->setCurrentY(intval($_POST['currentY']));
  $bp->setCurrentAngle(intval($_POST['currentAngle']));
  $bp->setMapStatus(intval($_POST['mapStatus']));
  $action->setCurrentX(intval($_POST['currentX']));
  $action->setCurrentY(intval($_POST['currentY']));
  $action->setCurrentAngle(intval($_POST['currentAngle']));
  $action->setMapStatus(intval($_POST['mapStatus']));
  
  
} else {
  $bp->setCurrentX(0);
  $bp->setCurrentY(1);
  $bp->setCurrentAngle(0);
  $bp->setMapStatus(0);
  $action->setCurrentX(0);
  $action->setCurrentY(1);
  $action->setCurrentAngle(0);
  $action->setMapStatus(0);
  $_SESSION = [];
// var_dump($_SESSION);

// Initialiser la variable d'inventaire à un tableau vide si elle n'existe pas déjà
if (!isset($_SESSION['inventaire'])) {
    $_SESSION['inventaire'] = array();
    // var_dump($_SESSION['inventaire']);
}
  $bp->RazAction();

}

// Traitez les déplacements soumis par le formulaire (Différents S_POST activant mouvement)
if (isset($_POST['action'])) {
    // Exécutez la méthode correspondant à l'action soumise (goForward, goBack, etc.)
    switch ($_POST['action']) {
      case 'goForward':
          $bp->goForward();
          $action->goForward();
        break;

      case 'goBack':
          $bp->goBack();
          $action->goBack();
        break;

      case 'goRight':
          $bp->goRight();
          $action->goRight();
        break;

      case 'goLeft':
          $bp->goLeft();
          $action->goLeft();
        break;
        
        case 'turnRight':
          $bp->turnRight();
          $action->goRight();
        break;

      case 'turnLeft':
          $bp->turnLeft();
          $action->turnLeft();
        break;

        case 'action' :
          $action->doAction();
        
      default:
        // Action inconnue, ne rien faire
        break;
    }
  }

// Définir le chemin d'accès aux images
$imagePath = 'images/';

// Générez le layout HTML
?>
<!DOCTYPE html>
<html>

<head>
    <title>Projet Serval</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Affichage image que renvoie BD selon coordonnées et la statut de la maps -->
    <div id="first-person-view">
        <img class="first-person-view" src="<?php echo $fpv->getView($bp); ?>">

        <!-- Description de l'inventaire -->
        <div class="inventaire">
            <p><span>INVENTAIRE</span> :
                <?php echo isset($_SESSION['description']) ? $_SESSION['description'] : "vide"; ?></p>
        </div>
    </div>

    <!-- Afficher texte selon position et angle-->
    <div id="text">
        <h2><?php echo $fpt->getText($bp);?></h2>
        <div class="row">
            <form method="post" action="index.php">

                <!-- Guetter pour conserver information après chaque submit -->
                <input type="hidden" name="currentX" value="<?php echo $bp->getCurrentX(); ?>">
                <input type="hidden" name="currentY" value="<?php echo $bp->getCurrentY(); ?>">
                <input type="hidden" name="currentAngle" value="<?php echo $bp->getCurrentAngle();?>">
                <input type="hidden" name="mapStatus" value="<?php echo $bp->getMapStatus(); ?>">

                <!-- Afficher les boutons de contrôle pour les déplacements -->
                <button type="submit" name="action" value="goForward" class="green center"
                    <?php echo $bp->checkForward() == TRUE ? "enabled" : "disabled"; ?>>Avancer</button>
                <button type="submit" name="action" value="turnLeft" class="blue">Tourner à gauche</button>
                <button type="submit" name="action" value="goLeft" class="yellow"
                    <?php echo $bp->checkLeft() == TRUE ? "enabled" : "disabled"; ?>>Gauche</button>
                <button type="submit" name="action" value="goRight" class="yellow"
                    <?php echo $bp->checkRight() == TRUE ? "enabled" : "disabled"; ?>>Droite</button>
                <button type="submit" name="action" value="turnRight" class="blue">Tourner<br>à<br>droite</button>
                <button type="submit" name="action" value="goBack" class="green center"
                    <?php echo $bp->checkBack() == TRUE ? "enabled" : "disabled"; ?>>Reculer</button>
                <button type="submit" name="action" value="action" class="green center"
                    <?php echo $action->checkAction() == TRUE ? "enabled" : "disabled"; ?>>action</button>
            </form>
        </div>

        <!-- Afficher le statut de l'action en cours -->
        <div class="action-status">
            <p><?php echo $action->getStatusAction(); ?></p>
        </div>
    </div>

    <!-- boussole -->
    <div id="compass">
        <img class="compass <?php echo $fpv->getAnimCompass($bp); ?>">
        <div class="n"></div>
        <div class="e"></div>
        <div class="s"></div>
        <div class="w"></div>
    </div>

</body>

</html>