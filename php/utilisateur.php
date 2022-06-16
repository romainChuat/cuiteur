<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */


ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';


$bd = hr_bd_connect();

$id = (int)$_GET['id']; //on n'est jamais trop prudent

//RECUPERE INFO USER GET
$sql = "SELECT * FROM users WHERE usID = '".$id."'";
$res = hr_bd_send_request($bd, $sql);


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

//RECUPERE INFO USER SESSION
$sql = "SELECT * FROM users WHERE usID = ".$_SESSION['usID']."";
$request = hr_bd_send_request($bd,$sql);

$t =  mysqli_fetch_assoc($res);

hr_aff_debut('Cuiteur | '.hr_html_proteger_sortie($t['usPseudo']).'','../styles/cuiteur.css');
hr_aff_entete('Le profil de '.$t['usPseudo']);
rc_aff_infosV2($request);

//AJOUT D'UN ABONNEMENT
if(isset($_POST['btnSAbonner'])){
    $follower = $_SESSION['usID'];
    $followed = $_GET['id'];
    $date_abonnement = date('Ymd');
    $sql = "INSERT INTO estabonne(eaIDUser,eaIDAbonne,eaDate) VALUES ($follower,$followed,$date_abonnement)";
    hr_bd_send_request($bd,$sql);
    Header('Refresh:0');
}

//RETIRE UN ABONNEMENT
if(isset($_POST['btnDesabonner'])){
    $follower = $_SESSION['usID'];
    $followed = $_GET['id'];
    $sql = "DELETE FROM estabonne WHERE eaIDUser = $follower AND eaIDAbonne = $followed";
    hr_bd_send_request($bd,$sql);
    Header('Refresh:0');
}

//GET INFO USERS
$sql = "SELECT * FROM users WHERE usID = $id";
$res = hr_bd_send_request($bd,$sql);
$t = mysqli_fetch_assoc($res);

hr_aff_user($bd,$_GET['id'],$t);
hrl_aff_info_user($bd,$_GET['id'],$t);

mysqli_free_result($res);
mysqli_close($bd);

hr_aff_pied();
hr_aff_fin();

function hrl_aff_info_user(mysqli $bd, int $id, array $t):void{
    $naissance = hr_html_proteger_sortie(hr_amj_clair($t['usDateNaissance'])) === '' ? 'Non renseigné(e)' : hr_html_proteger_sortie(hr_amj_clair($t['usDateNaissance']));
    $inscription = hr_html_proteger_sortie(hr_amj_clair($t['usDateInscription'])) === '' ? 'Non renseigné(e)' : hr_html_proteger_sortie(hr_amj_clair($t['usDateInscription']));
    $ville = $t['usVille'] === '' ? 'Non renseigné(e)' : hr_html_proteger_sortie($t['usVille']);
    $bio = $t['usBio'] === '' ? 'Non renseigné(e)' : hr_html_proteger_sortie($t['usBio']);
    $web = $t['usWeb'] === '' ? 'Non renseigné(e)' : hr_html_proteger_sortie($t['usWeb']);;

    echo '<form id="userInfo" method="post" action="utilisateur.php?id='.urlencode($id).'">',
        '<table >',
        '<tr>',
            '<td><label for=""> Date de naissance : </label></td>',
            '<td><p>'.$naissance.'<p></td>',
        '</tr>',
        '<tr>',
            '<td><label for=""> Date d\'inscription : </label></td>',
            '<td><p>'.$inscription.'<p></td>',
        '</tr>',
        '<tr>',
            '<td><label for=""> Ville de résidence : </label></td>',
            '<td><p>'.$ville.'<p></td>',
        '</tr>',
        '<tr class="containerBio">',
            '<td><label for=""> Mini-bio : </label></td>',
            '<td><p>'.$bio.'<p></td>',
        '</tr>',
        '<tr>',
            '<td><label for=""> Site web : </label></td>',
            '<td><p>'.$web.'<p></td>',
        '</tr>',
        '<tr>';
    
    


    if($id === $_SESSION['usID']){
      echo  '</table>',
        '</form>';
    }else{
        hrl_aff_profil_not_user($bd,$t);
    }
}


function hrl_aff_profil_not_user($bd,array $t = array()):void{
    $follower = $_SESSION['usID'];
    $followed = $_GET['id'];
    $sql = "SELECT eaIDUser FROM estabonne WHERE eaIDUser = $follower AND eaIDAbonne = $followed";
    $res = hr_bd_send_request($bd,$sql);

    if(mysqli_num_rows($res) === 0){
        echo '<td colspan="2">',
                    '<input type="submit" name="btnSAbonner" value="S\'abonner">',
                '</td>',
                '</tr>',
            '</table>',
            '</form>';
    }else{
        echo '<td colspan="2">',
                    '<input type="submit" name="btnDesabonner" value="Se Désabonner">',
                '</td>',
                '</tr>',
            '</table>',
            '</form>';
    }
   
    
    
}



ob_end_flush();

?>
