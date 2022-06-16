<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start(); //démarre la bufferisation
session_start();

require_once 'bibli_generale.php';
require_once 'bibli_cuiteur.php';

$bd = hr_bd_connect();

$sql = "SELECT * FROM users WHERE usID = ".$_SESSION['usID']."";
$request = hr_bd_send_request($bd,$sql);

hr_aff_debut('Cuiteur | Compte','../styles/cuiteur.css');
hr_aff_entete('Paramètres de mon compte');
rc_aff_infosV2($request);

hrl_aff_account_settings($bd);

hr_aff_pied();
hr_aff_fin();

mysqli_close($bd);

function hrl_aff_account_settings(mysqli $bd):void{
    $errPerso = isset($_POST['btnValiderPerso']) ? hrl_traitement_info_perso() : array(); 
    $errAccount = isset($_POST['btnValiderAccount']) ? hrl_traitement_info_account() : array(); 
    $errSettings = isset($_POST['btnValiderSettings']) ? hrl_traitement_account_settings() : array(); 

    echo '<p>Cette page vous permet de modifier les informations relatives à votre compte.</p>';

    rc_aff_titre_section('Informations personnelles');
    hr_aff_info_perso($bd,$errPerso);

    rc_aff_titre_section('Informations sur votre compte Cuiteur');
    hr_aff_info_account($bd,$errAccount);

    rc_aff_titre_section('Paramètres de votre compte Cuiteur');
    hr_aff_settings_account($bd,$errSettings);   
}



/*
TRAITEMENT DES DONNEES (NOM,DATE NAISSANCE,VILLE,BIO)
*/
function hrl_traitement_info_perso():array{
    if( !hr_parametres_controle('post', array('nomprenom','naissance','btnValiderPerso'),array('ville','bio',))) {
        hr_session_exit();   
    }

    foreach($_POST as &$val){
        $val = trim($val);
    }

    $erreurs = array();

    // vérification des noms et prenoms
    if (empty($_POST['nomprenom'])) {
        $erreurs[] = 'Le nom et le prénom doivent être renseignés.'; 
    }
    else {
        if (mb_strlen($_POST['nomprenom'], 'UTF-8') > LMAX_NOMPRENOM){
            $erreurs[] = 'Le nom et le prénom ne peuvent pas dépasser ' . LMAX_NOMPRENOM . ' caractères.';
        }
        $noTags = strip_tags($_POST['nomprenom']);
        if ($noTags != $_POST['nomprenom']){
            $erreurs[] = 'Le nom et le prénom ne peuvent pas contenir de code HTML.';
        }
        else {
            if( !mb_ereg_match('^[[:alpha:]]([\' -]?[[:alpha:]]+)*$', $_POST['nomprenom'])){
                $erreurs[] = 'Le nom et le prénom contiennent des caractères non autorisés.';
            }
        }
    }


    // vérification de la date de naissance
    if (empty($_POST['naissance'])){
        $erreurs[] = 'La date de naissance doit être renseignée.'; 
    }
    else{
        if( !mb_ereg_match('^\d{4}(-\d{2}){2}$', $_POST['naissance'])){ //vieux navigateur qui ne supporte pas le type date ?
            $erreurs[] = 'la date de naissance doit être au format "AAAA-MM-JJ".'; 
        }
        else{
            list($annee, $mois, $jour) = explode('-', $_POST['naissance']);
            if (!checkdate($mois, $jour, $annee)) {
                $erreurs[] = 'La date de naissance n\'est pas valide.'; 
            }
            else if (mktime(0,0,0,$mois,$jour,$annee + AGE_MIN) > time()) {
                $erreurs[] = 'Vous devez avoir au moins '.AGE_MIN.' ans pour vous inscrire.'; 
            }
            else if (mktime(0,0,0,$mois,$jour,$annee + AGE_MAX + 1) < time()) {
                $erreurs[] = 'Vous devez avoir au plus '.AGE_MAX.' ans pour vous inscrire.'; 
            }
        }
    }

    // vérification de la ville
    if(mb_strlen($_POST['ville'],'UTF-8') > LMAX_VILLE){
        $erreurs[] = 'Le nom de la ville ne doit pas dépasser '.LMAX_VILLE.' caractères';
    }
    if(isHTML($_POST['ville'])){
        $erreurs[] = 'Le nom de la ville ne doit pas contenir du code HTML';
    }
    
    // vérification de la bio
    if(mb_strlen($_POST['bio'],'UTF-8') > LMAX_BIO){
        $erreurs[] = 'La bio cuiteur ne doit pas dépasser '.LMAX_BIO.' caractères';
    }
    if(isHTML($_POST['bio'])){
        $erreurs[] = 'La bio cuiteur ne doit pas contenir du code HTML';
    }
    

    return $erreurs;
}





/*
TRAITEMENT DES DONNEES (EMAIL,SITE)
*/
function hrl_traitement_info_account():array{
    if( !hr_parametres_controle('post', array('mail','btnValiderAccount'),array('site'))) {
        hr_session_exit();   
    }

    foreach($_POST as &$val){
        $val = trim($val);
    }

    $erreurs = array();

    // vérification du format de l'adresse email
    if (empty($_POST['mail'])){
        $erreurs[] = 'L\'adresse mail ne doit pas être vide.'; 
    }
    else {
        if (mb_strlen($_POST['mail'], 'UTF-8') > LMAX_EMAIL){
            $erreurs[] = 'L\'adresse mail ne peut pas dépasser '.LMAX_EMAIL.' caractères.';
        }
        // la validation faite par le navigateur en utilisant le type email pour l'élément HTML input
        // est moins forte que celle faite ci-dessous avec la fonction filter_var()
        // Exemple : 'l@i' passe la validation faite par le navigateur et ne passe pas
        // celle faite ci-dessous
        if(! filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L\'adresse mail n\'est pas valide.';
        }
    }

    // vérification de l'adresse du site
    if(!empty($_POST['site'])){
        if(mb_strlen($_POST['site'],'UTF-8') > LMAX_WEB){
            $erreurs[] = 'L\'adresse du site ne doit pas dépasser '.LMAX_WEB.' caractères'; 
        }
        if(!filter_var($_POST['site'],FILTER_VALIDATE_URL)){
            $erreurs[] = 'L\'adresse du site n\'est pas syntaxiquement correct'; 
        }
        $file = $_POST['site'];
        $file_headers = @get_headers($file);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $erreurs[] = 'L\'adresse du site n\'existe pas'; 
        }
    }
    
    return $erreurs;
}





/*
TRAITEMENT DES DONNEES (MOT DE PASSE,PHOTO)
*/
function hrl_traitement_account_settings():array{
    if( !hr_parametres_controle('post', array('btnValiderSettings'),array('passe1','passe2','image','dontUsePhoto','usePhoto'))) {
        hr_session_exit();   
    }

    foreach($_POST as &$val){
        $val = trim($val);
    }

    $erreurs = array();

    // vérification mot de passe
    if(!empty($_POST['passe1'])){
        if ($_POST['passe1'] !== $_POST['passe2']) {
            $erreurs[] = 'Les mots de passe doivent être identiques.';
        }
        $nb = mb_strlen($_POST['passe1'], 'UTF-8');
        if ($nb < LMIN_PASSWORD || $nb > LMAX_PASSWORD){
            $erreurs[] = 'Le mot de passe doit être constitué de '. LMIN_PASSWORD . ' à ' . LMAX_PASSWORD . ' caractères';
        }
    }
    

    // vérification photo
    if(isset($_FILES['image']) && isset($_POST['usePhoto'])){
        $info = getimagesize($_FILES['image']['tmp_name']);
        $temp = explode(".", $_FILES['image']['name']);
        if($_FILES['image']['size']/1024 > 20){
            echo $_FILES['image']['size'];
            $erreurs[] = 'Cette image est trop lourde (>20ko)';
        }
        if(end($temp) !== 'jpg'){
            $erreurs[] = 'Ce type de fichier n\'est pas accepté';
        }
    }
    if(isset($_FILES['image']) && !isset($_POST['dontUsePhoto']) && !isset($_POST['usePhoto'])){
        $erreurs[] = 'Vous devez choisir si vous voulez utiliser l\'image ou non';
    }

    return $erreurs;
}

ob_end_flush();

?>
