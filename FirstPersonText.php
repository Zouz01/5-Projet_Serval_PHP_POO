<?php
class FirstPersonText extends BaseClass {
   
    // Récupère le texte associé à la carte et au statut de l'action en cours
    public function getText(BaseClass $bp) {
        $mapId = $bp->getMapId();

        // Requête SQL pour récupérer le texte associé à la carte et au statut de l'action en cours
        $sql = "SELECT text FROM text WHERE map_id = :mapId AND status_action = :statusAction";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':mapId', $mapId, PDO::PARAM_INT);
        $statusAction = $this->getStatusAction($bp);
        $stmt->bindParam(':statusAction', $statusAction, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        // Renvoie le texte s'il existe
        if (isset($res->text)) {
            return $res->text;
        }
    }

    // Récupère le nombre d'actions en cours pour la carte en cours
    public function getStatusAction(BaseClass $bp) {
        $mapId = $bp->getMapId();

        // Requête SQL pour récupérer le nombre d'actions en cours pour la carte en cours
        $stmt = $this->_dbh->prepare("SELECT * FROM actions WHERE map_id = :mapId AND status = 0");
        $stmt->bindParam(':mapId', $mapId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Renvoie le nombre d'actions en cours
        return count($result);
    }
}