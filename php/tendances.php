<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';


$bd = hr_bd_connect();

$tag = $_GET['tag'];
$tag === ''? $tag = 'Tendances' : $tag = $tag;

hr_aff_debut('Cuiteur | Tendances','../styles/cuiteur.css');
hr_aff_entete($tag);
$sql = "SELECT * FROM users WHERE usID = ".$_SESSION['usID']."";
$res = hr_bd_send_request($bd,$sql);
rc_aff_infosV2($res);


if($tag === 'Tendances'){
    $today = date('Ymd');
    rc_aff_tend($bd,'Top 10 du jour',$today);

    $month = date('m');
    $day = date('d');
    $year = date('Y');
    $monday=strtotime("monday this week", mktime(0,0,0, $month, $day, $year));
    $monday=  date("Ymd",$monday);
    rc_aff_tend($bd,'Top 10 semaine',$today,$monday);

    $mois =  date('m');
    $year = date('Y');
    $first_day_month = $year.$mois.'01';
    rc_aff_tend($bd,'Top 10 mois',$today,$first_day_month);

    $year = date('Y');
    $first_day_year = $year.'01'.'01';
    rc_aff_tend($bd,'Top 10 année ',$today,$first_day_year);

}else{
    $nb_blablas = 4; //nombre de blablas affiché au premier chargement de la page
    if(isset($_GET['more'])){
        $nb_blablas  = $_GET['nb'];
    }
    $tag = $_GET['tag'];
    $sql = "SELECT taID,taIDBlabla,COUNT(*) AS NBtag FROM tags WHERE taID='$tag'";
    $res = hr_bd_send_request($bd,$sql);
    $t = mysqli_fetch_assoc($res);
    $nb_blablas_total = $t['NBtag'];

    $sql = "SELECT taID, taIDBlabla,blID,blTexte,blDate,blHeure,
            auteur.usID AS autID, auteur.usPseudo AS autPseudo, auteur.usNom AS autNom, auteur.usAvecPhoto AS autPhoto, 
            origin.usID AS oriID, origin.usPseudo AS oriPseudo, origin.usNom AS oriNom, origin.usAvecPhoto AS oriPhoto
            FROM users AS auteur INNER JOIN blablas ON auteur.usID = blIDAuteur INNER JOIN tags ON blID = taIDBlabla 
            LEFT OUTER JOIN users AS origin ON origin.usID = blIDAutOrig
            WHERE taID = '$tag'
            ORDER BY blID DESC 
            LIMIT $nb_blablas";
    $res = hr_bd_send_request($bd,$sql);
    rc_aff_blablasV2($res);
    hr_aff_more_blablas_tendance($nb_blablas,'tendances.php',$nb_blablas_total,$tag);
}

mysqli_free_result($res);
mysqli_close($bd);

hr_aff_pied();
hr_aff_fin();

ob_end_flush();

?>
