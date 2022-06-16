<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start();
session_start();

require_once 'bibli_cuiteur.php';
require_once 'bibli_generale.php';


if(!hr_est_authentifie()){
    header('Location:../index.php');
}

$bd = hr_bd_connect();
$id = $_SESSION['usID'];
$sql = "SELECT * FROM users WHERE usID =$id";
$requet = hr_bd_send_request($bd,$sql);

if(isset($_POST['btnValider'])){ 
    rc_gestion_sub_unsub($id,$bd);
}

hr_aff_debut('Cuiteur | Suggestion','../styles/cuiteur.css');
hr_aff_entete('Suggestions');
rc_aff_infosV2($requet);



$nbUserMax = 5;

$sql =      "SELECT DISTINCT usID, usPseudo,usNom, usAvecPhoto
            FROM users INNER JOIN estabonne ON usID=eaIDAbonne
            WHERE eaIDUser IN (SELECT eaIDAbonne FROM estabonne WHERE eaIDuser=$id)
            AND usID!=$id
            AND usID NOT IN (SELECT eaIDAbonne FROM estabonne WHERE eaIDuser=$id)
            ORDER BY usID LIMIT $nbUserMax";
$requet = hr_bd_send_request($bd,$sql);

$nbUser = mysqli_num_rows($requet);
if($nbUser<$nbUserMax){
    $nbUserTest = $nbUserMax - $nbUser;
    $sql = "SELECT usID, usPseudo, COUNT(usID), dejaAbonne.eaIDUser
            FROM (users INNER JOIN estabonne ON usID=eaIDUser) LEFT OUTER JOIN estabonne AS dejaAbonne ON usID=dejaAbonne.eaIDAbonne AND dejaAbonne.eaIDUser=$id
            GROUP BY usID
            ORDER BY COUNT(usID) DESC
            LIMIT $nbUserTest;";


}
$requet1 = hr_bd_send_request($bd,$sql);
rc_aff_suggestion($requet,$requet1);


mysqli_free_result($requet);
mysqli_close($bd);

hr_aff_fin();
hr_aff_pied();

ob_end_flush();



/**
 * Affiche les resultats de la suggestions 
 * 
 * @param mysqli_result $r Objet permettant l'accès aux résultats de la requête SELECT des abonnees des abonnees de l'utilisateur courant
 * @param mysqli_result $r Objet permettant l'accès aux résultats de la requête SELECT des utilisateurs possedant le plus d'abonnees
 * 
 */
function rc_aff_suggestion(mysqli_result $r,mysqli_result $r1): void{
    $bd=hr_bd_connect();
    echo '<form method="post" action="suggestion.php">';
    echo '<ul>';
    $tab_user = array();
    while($t = mysqli_fetch_assoc($r)){
        $user_id = $t['usID'];
        array_push($tab_user, $user_id);
        $nom = $t['usNom'];
        $pseudo = $t['usPseudo'];
        $photo = $t['usAvecPhoto'];
        hr_aff_abo($bd,$user_id);
    }
    $empty = true;
    while($t = mysqli_fetch_assoc($r1)){
        $user_id = $t['usID'];
        if($user_id !== $_SESSION['usID'] && $t['eaIDUser'] === NULL && !in_array($user_id, $tab_user)){
            hr_aff_abo($bd,$user_id);
            $empty = false;
        }
    }
    echo '</ul>';

    if(mysqli_num_rows($r) === 0 && (mysqli_num_rows($r1) === 0 || $empty)){
        rc_aff_res('Aucune suggestion');
    }else{
        echo '<input id="validerAbo" type="submit" name="btnValider" value="Valider">';
    }
    echo '</form>';
}

?>