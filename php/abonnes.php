<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';

$bd = hr_bd_connect();

//INFORMATIONS USER GET
$id = (int)$_GET['id']; //on n'est jamais trop prudent
$sql = "SELECT * FROM users WHERE usID = '".$id."'";
$res = hr_bd_send_request($bd, $sql);//INFOS de GET['id']
$t =  mysqli_fetch_assoc($res);

//UTILISATEUR INTROUVABLE
if (mysqli_num_rows($res) == 0){
    // libération des ressources
    mysqli_free_result($res);

    hr_aff_debut('Cuiteur | utilisateur introuvable','../styles/cuiteur.css');
    hr_aff_entete('utilisateur introuvable');
    rc_aff_infosV2($request);
    echo    '<ul>',
                '<li>L\'utilisateur ', $id, ' n\'existe pas</li>',
            '</ul>';
    hr_aff_pied();
    mysqli_close($bd);
    hr_aff_fin();
    exit;   //==> FIN DU SCRIPT
}

//INFORMATIONS USER SESSION
$sql = "SELECT * FROM users WHERE usID = ".$_SESSION['usID']."";
$request = hr_bd_send_request($bd,$sql);

hr_aff_debut('Cuiteur | Abonnes de '.hr_html_proteger_sortie($t['usPseudo']).'','../styles/cuiteur.css');
hr_aff_entete('Les abonnes de '.$t['usPseudo']);
rc_aff_infosV2($request);

$sql = "SELECT usID,usPSeudo
FROM (users INNER JOIN estabonne ON usID=eaIDUser) WHERE estabonne.eaIDAbonne=".$_GET['id']."";
$res = hr_bd_send_request($bd,$sql);//ABONNES DE GET['id']



echo '<form method="post" action="abonnes.php?id='.urlencode($_GET['id']).'">',
'<ul >';

//CHECK IF USER IS SUBSCRIBED TO $id
$sql = "SELECT * FROM estabonne WHERE eaIDAbonne = ".$_SESSION['usID']." AND eaIDUser = $id";
$requestSub = hr_bd_send_request($bd,$sql);
if(mysqli_num_rows($requestSub) === 0){//USER DON'T FOLLOW $id
    hr_aff_user($bd,$_GET['id'],$t,false,$_GET['id']===$_SESSION['usID']);
}else{
    hr_aff_user($bd,$_GET['id'],$t,true,$_GET['id']===$_SESSION['usID']);
}
if(isset($_POST['btnValider'])){ 
    rc_gestion_sub_unsub($id,$bd);
}
while($t = mysqli_fetch_assoc($res)){
    hr_aff_abo($bd,$t['usID']);
}

echo '<input id="validerAbo" type="submit" name="btnValider" value="Valider">',
'</ul>',
'</form>';

hr_aff_pied();
hr_aff_fin();

mysqli_free_result($res);
mysqli_close($bd);


/**
 * Abonne l'utilisateur 
 * @param user Abonné
 * @param id abonnement
 */
function hrl_sub_user(mysqli $bd,int $id,int $user):void{
    $date_abonnement = date('Ymd');
    $sql = "INSERT INTO estabonne(eaIDUser,eaIDAbonne,eaDate) VALUES ($user,$id,$date_abonnement)";
    hr_bd_send_request($bd,$sql);
}

/**
 * Désabonne l'utilisateur 
 * @param user Abonné
 * @param id désabonnement
 */
function hrl_unsub_user(mysqli $bd,int $id,int $user):void{
    $sql = "DELETE FROM estabonne WHERE eaIDUser = $user AND eaIDAbonne = $id";
    hr_bd_send_request($bd,$sql);
}

ob_end_flush();

?>