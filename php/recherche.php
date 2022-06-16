<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';

$bd = hr_bd_connect();

hr_aff_debut('Cuiteur | Rechercher','../styles/cuiteur.css');
hr_aff_entete('Rechercher des utilisateurs');

$id = $_SESSION['usID']  ;

$sql = "SELECT * FROM users WHERE usID = $id";
$res = hr_bd_send_request($bd,$sql);
rc_aff_infosV2($res);
$err = isset($_POST['btnRecherche']) ? rcl_traitement_recherche() : array(); 

rcl_aff_recherche($err);
if(count($err)===0 && isset($_POST['btnRecherche'])){
    rcl_aff_res_recherche();
}


if(isset($_POST['btnValider'])){ 
    rc_gestion_sub_unsub($id,$bd);
}


mysqli_free_result($res);
mysqli_close($bd);

hr_aff_pied();
hr_aff_fin();

ob_end_flush();


/**
 * Affiche les resultats de la recherche
 */
function rcl_aff_res_recherche(): void{
    $bd = hr_bd_connect();
    $search = $_POST['recherche'];
    $id = $_SESSION['usID'];

    $sql =  "SELECT usID,usPseudo,usAvecPhoto,usNom,usAvecPhoto,eaIDAbonne
            FROM users LEFT OUTER JOIN estabonne ON usID=eaIDAbonne AND eaIDuser=$id
            WHERE usPseudo LIKE '%$search%' OR usNom LIKE '%$search%'
            ORDER BY usPseudo";
    $res = hr_bd_send_request($bd,$sql);
    $nb_resultats = mysqli_num_rows($res);
    if($nb_resultats !== 0){
        rc_aff_titre_section('Résultats de la recherche');
        echo '<form method="post" action="recherche.php">';
        while($t = mysqli_fetch_assoc($res)){
            $usID = $t['usID'];
            if($usID !== $_SESSION['usID']){
                hr_aff_abo($bd,$usID);
            }
        }
        echo '<input id="validerAbo" type="submit" name="btnValider" value="Valider">',
            '</form>';
    }else{
        rc_aff_res('Aucun résultat');
    }
}

/**
 * Traite les erreurs saisie dans la barre de recherche
 * 
 * On teste une valeur a été saisie et si elle ne contient aucun code HTML
 * 
 * @return array $err le tableau d'erreur de saisie
 */
function rcl_traitement_recherche(): array {
    if(!hr_parametres_controle('post', array('recherche','btnRecherche')) || isHTML($_POST['recherche']) ){
        hr_session_exit();
    }
    foreach($_POST as &$val){
        $val = trim($val);
    }
    $err = array();
    if(mb_strlen($_POST['recherche'])===0){
        $err['text'] = 'votre recherche est vide';
    }
    if(count($err)>0){
        return $err;
    }
    return $err;
}
?>