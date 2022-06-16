<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';


// si l'utilisateur n'est pas authentifié, on le redirige sur la page index.php
if (! hr_est_authentifie()){
    header('Location: ../index.php');
    exit;
}

hr_aff_debut('Cuiteur | Protégée');


$bd = hr_bd_connect();

$sql = "SELECT *
        FROM users
        WHERE usID = {$_SESSION['usID']}";

$res = hr_bd_send_request($bd, $sql);

$T = mysqli_fetch_assoc($res);

mysqli_free_result($res);
mysqli_close($bd);

$T = hr_html_proteger_sortie($T);

echo '<h1>Accès restreint aux utilisateurs authentifiés</h1>';

echo '<ul>';
echo '<li><strong>ID : ', $_SESSION['usID'], '</strong></li>';
echo '<li>SID : ', session_id(), '</li>';
foreach($T as $cle => $val){
    echo '<li>', $cle, ' : ', $val, '</li>';
} 
echo '</ul>'; 

hr_aff_fin();

ob_end_flush();


?>
