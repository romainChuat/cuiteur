<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start();
session_start();

require_once 'bibli_cuiteur.php';
require_once 'bibli_generale.php';

$bd = hr_bd_connect();
$user_id = $_GET['id'];
$nb_blablas = 4; //nombre de blablas affiché au premier chargement de la page
$sql = "SELECT COUNT(meIDUser) AS nb FROM mentions WHERE meIDUser=$user_id";
$requet = hr_bd_send_request($bd,$sql);
$t = mysqli_fetch_assoc($requet);
$nb_blablas_total = $t['nb'];
if(isset($_GET['more'])){
    $nb_blablas  = $_GET['nb'];
}


$sql =  "SELECT * FROM users WHERE usID=$user_id";
$requet = hr_bd_send_request($bd,$sql);
$t = mysqli_fetch_assoc($requet);
$pseudo = $t['usPseudo'];
$photo = $t['usAvecPhoto'];
$nom = $t['usNom'];

hr_aff_debut('Cuiteur | Mentions','../styles/cuiteur.css');
hr_aff_entete("Les mentions de $pseudo");

$id = $_SESSION['usID'];
$sql = "SELECT * FROM users WHERE usID = $id";
$res = hr_bd_send_request($bd,$sql);
rc_aff_infosV2($res);


hr_aff_user($bd,$_GET['id'],$t);


$sql = "SELECT blID, blTexte, blDate, blHeure, meIDBlabla, meIDUser,
        auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autPhoto, 
        origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom AS oriNom, origin.usAvecPhoto AS oriPhoto
        FROM users AS auteur INNER JOIN blablas ON auteur.usID = blIDAuteur INNER JOIN mentions ON blID = meIDBlabla 
        LEFT OUTER JOIN users AS origin ON origin.usID = blIDAutOrig
        WHERE meIDUser = $user_id
        ORDER BY meIDBlabla DESC
        LIMIT $nb_blablas";
$requet = hr_bd_send_request($bd,$sql);




rc_aff_blablasV2($requet);



rc_aff_more_blablas($nb_blablas,'mentions.php',$nb_blablas_total,$user_id);


hr_aff_pied();
hr_aff_fin();

mysqli_free_result($requet);
mysqli_free_result($res);
mysqli_close($bd)

?>