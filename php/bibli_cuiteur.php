<?php
/* ROUGETET HUGO --  CHUAT ROMAIN */




/*********************************************************
 *        Bibliothèque de fonctions spécifiques          *
 *               à l'application Cuiteur                 *
 *********************************************************/

 // Force l'affichage des erreurs
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting( E_ALL );

// Définit le fuseau horaire par défaut à utiliser. Disponible depuis PHP 5.1
date_default_timezone_set('Europe/Paris');

//définition de l'encodage des caractères pour les expressions rationnelles multi-octets
mb_regex_encoding ('UTF-8');

define('IS_DEV', true);//true en phase de développement, false en phase de production

 // Paramètres pour accéder à la base de données
define('BD_SERVER', 'localhost');
define('BD_NAME', 'cuiteur_bd');
define('BD_USER', 'cuiteur_userl');
define('BD_PASS', 'cuiteur_passl');
/*define('BD_NAME', 'login_cuiteur');
define('BD_USER', 'login_u');
define('BD_PASS', 'login_p');*/


// paramètres de l'application
define('LMIN_PSEUDO', 4);
define('LMAX_PSEUDO', 30); //longueur du champ dans la base de données
define('LMAX_EMAIL', 80); //longueur du champ dans la base de données
define('LMAX_NOMPRENOM', 60); //longueur du champ dans la base de données


define('LMIN_PASSWORD', 4);
define('LMAX_PASSWORD', 20);

define('AGE_MIN', 18);
define('AGE_MAX', 120);

define('LMAX_VILLE', 50);
define('LMAX_BIO', 255);
define('LMAX_WEB', 120);


//_______________________________________________________________
/**
 * Génération et affichage de l'entete des pages
 *
 * @param ?string    $titre  Titre de l'entete (si null, affichage de l'entete de cuiteur.php avec le formulaire)
 */
function hr_aff_entete(?string $titre = null):void{
    echo '<div id="bcContenu">',
            '<header>',
                '<a href="deconnexion.php" title="Se déconnecter de cuiteur"></a>',
                '<a href="cuiteur.php" title="Ma page d\'accueil"></a>',
                '<a href="recherche.php" title="Rechercher des personnes à suivre"></a>',
                '<a href="compte.php" title="Modifier mes informations personnelles"></a>';
    if ($titre === null){
        echo    '<form action="../php/cuiteur.php" method="POST">',
                    '<textarea name="txtMessage"></textarea>',
                    '<input type="submit" name="btnPublier" value="" title="Publier mon message">',
                '</form>';
    }
    else{
        echo    '<h1>', $titre, '</h1>';
    }
    echo    '</header>';    
}
function hr_aff_entete_empty(?string $titre = null):void{
    echo '<div id="bcContenu">',
            '<header id="headerEmpty">';
    if ($titre === null){
        echo    '<form action="../php/cuiteur.php" method="POST">',
                    '<textarea name="txtMessage"></textarea>',
                    '<input type="submit" name="btnPublier" value="" title="Publier mon message">',
                '</form>';
    }
    else{
        echo    '<h1>', $titre, '</h1>';
    }
    echo    '</header>';    
}
function hr_aff_enteteV2(mixed $auteur ,?string $titre = null):void{
    echo '<div id="bcContenu">',
            '<header>',
                '<a href="deconnexion.php" title="Se déconnecter de cuiteur"></a>',
                '<a href="cuiteur.php" title="Ma page d\'accueil"></a>',
                '<a href="recherche.php" title="Rechercher des personnes à suivre"></a>',
                '<a href="compte.php" title="Modifier mes informations personnelles"></a>';
    if ($titre === null){
        echo    '<form action="../php/cuiteur.php" method="POST">',
                    '<textarea name="txtMessage">',($auteur===null ? '' : "@$auteur" ), '</textarea>',
                    '<input type="submit" name="btnPublier" value="" title="Publier mon message">',
                '</form>';
    }
    else{
        echo    '<h1>', $titre, '</h1>';
    }
    echo    '</header>';    
}
//_______________________________________________________________
/**
 * Génération et affichage du bloc d'informations utilisateur
 *
 * @param bool    $connecte  true si l'utilisateur courant s'est authentifié, false sinon
 */
function hr_aff_infos(bool $connecte = true):void{
    echo '<aside>';
    if ($connecte){
        echo
            '<h3>Utilisateur</h3>',
            '<ul>',
                '<li>',
                    '<img src="../images/pdac.jpg" alt="photo de l\'utilisateur">',
                    //'<a href="utilisateur.php" title="Voir les infos de l\'utilisateur">'.hr_html_proteger_sortie($t['usPseudo']).'</a> '.hr_html_proteger_sortie($t['usNom']).'',
                    '<a href="utilisateur.php" title="Voir les infos de l\'utilisateur">pdac</a> Pierre Dac',
                '</li>',
                '<li><a href="../index.html" title="Voir la liste de mes messages">100 blablas</a></li>',
                '<li><a href="../index.html" title="Voir les personnes que je suis">123 abonnements</a></li>',
                '<li><a href="../index.html" title="Voir les personnes qui me suivent">34 abonnés</a></li>',                 
            '</ul>',
            '<h3>Tendances</h3>',
            '<ul>',
                '<li>#<a href="../index.html" title="Voir les blablas contenant ce tag">info</a></li>',
                '<li>#<a href="../index.html" title="Voir les blablas contenant ce tag">lol</a></li>',
                '<li>#<a href="../index.html" title="Voir les blablas contenant ce tag">imbécile</a></li>',
                '<li>#<a href="../index.html" title="Voir les blablas contenant ce tag">fairelafete</a></li>',
                '<li><a href="../index.html">Toutes les tendances</a><li>',
            '</ul>',
            '<h3>Suggestions</h3>',             
            '<ul>',
                '<li>',
                    '<img src="../images/yoda.jpg" alt="photo de l\'utilisateur">',
                    '<a href="../index.html" title="Voir mes infos">yoda</a> Yoda',
                '</li>',       
                '<li>',
                    '<img src="../images/paulo.jpg" alt="photo de l\'utilisateur">',
                    '<a href="../index.html" title="Voir mes infos">paulo</a> Jean-Paul Sartre',
                '</li>',
                '<li><a href="../index.html">Plus de suggestions</a></li>',
            '</ul>';
    }
    echo '</aside>',
         '<main>';   
}
//_______________________________________________________________
/**
 * Génération et affichage du bloc d'informations utilisateur
 *
 * @param mysqli_result $r Objet permettant l'accès aux résultats de la requête SELECT
 */
function rc_aff_infosV2(mysqli_result $r):void{
    $t =  mysqli_fetch_assoc($r);
    $photo = $t['usAvecPhoto'];
    $id_user = $t['usID'];
    if($photo == 1 && file_exists('../upload/'.$_SESSION['usID'].'.jpg')){
        $img = '<img src="../upload/'.$_SESSION['usID'].'.jpg" alt="photo de l\'utilisateur">';
    }else{
        $img = '<img src="../images/anonyme.jpg" alt="photo de l\'utilisateur">';
    }
    $pseudo = $t['usPseudo'];
    $nom = $t['usNom'];
    $bd = hr_bd_connect();
    //GET COUNT BLABLAS
    $sql = "SELECT COUNT(*) AS NB FROM blablas WHERE blIDAuteur = $id_user";
    $res = hr_bd_send_request($bd,$sql);
    $tBla = mysqli_fetch_assoc($res);
    //GET COUNT ABONNES
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDAbonne = $id_user";
    $res = hr_bd_send_request($bd,$sql);
    $tFol = mysqli_fetch_assoc($res);
    //GET COUNT ABONNEMENTS
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDUser = $id_user";
    $res = hr_bd_send_request($bd,$sql);
    $tSub = mysqli_fetch_assoc($res);
    //GET TENDANCES
    $nbTendance = 4;
    $sql = "SELECT taID, COUNT(*) AS NB
            FROM tags GROUP BY taID
            ORDER BY NB DESC LIMIT 0,$nbTendance";
    $tendRes = hr_bd_send_request($bd,$sql);

    echo '<aside>';
        echo
            '<h3>Utilisateur</h3>', 
            '<ul>',
                '<li>',
                    $img,
                    hr_html_a('utilisateur.php',hr_html_proteger_sortie($pseudo),'id',$id_user,'Voir mes infos').' '.hr_html_proteger_sortie($nom),
                '</li>',
                '<li>'.hr_html_a('blablas.php',$tBla['NB'].' blablas','id',$id_user,'Voir la liste de mes messages').'</li>',
                '<li>'.hr_html_a('abonnements.php',$tSub['NB'].' abonnements','id',$id_user,'Voir les personnes que je suis').'</li>',
                '<li>'.hr_html_a('abonnes.php',$tFol['NB'].' abonnés','id',$id_user,'Voir les personnes qui me suivent').'</li>',                 
            '</ul>',
            '<h3>Tendances</h3>',
            '<ul>';
            while($tend = mysqli_fetch_assoc($tendRes)){
                echo '<li>#<a href="tendances.php?tag='.urlencode(hr_html_proteger_sortie($tend['taID'])).'" title="Voir les blablas contenant ce tag">'.hr_html_proteger_sortie($tend['taID']).'</a></li>';
            }
                echo'<li><a href="tendances.php?tag=">Toutes les tendances</a><li>';
            echo '</ul>',
            '<h3>Suggestions</h3>',             
            '<ul>',
                '<li>',
                    '<img src="../images/yoda.jpg" alt="photo de l\'utilisateur">',
                    '<a href="utilisateur?id=5" title="Voir mes infos">yoda</a> Yoda',
                '</li>',       
                '<li>',
                    '<img src="../images/paulo.jpg" alt="photo de l\'utilisateur">',
                    '<a href="utilisateur?id=7" title="Voir mes infos">paulo</a> Jean-Paul Sartre',
                '</li>',
                '<li><a href="suggestion.php">Plus de suggestions</a></li>',
            '</ul>';
    echo '</aside>',
         '<main>';   
}
//_______________________________________________________________
/**
 * Génération et affichage du pied de page
 *
 */
function hr_aff_pied(): void{
    echo    '</main>',
            '<footer>',
                '<a href="../index.html">A propos</a>',
                '<a href="../index.html">Publicité</a>',
                '<a href="../index.html">Patati</a>',
                '<a href="../index.html">Aide</a>',
                '<a href="../index.html">Patata</a>',
                '<a href="../index.html">Stages</a>',
                '<a href="../index.html">Emplois</a>',
                '<a href="../index.html">Confidentialité</a>',
            '</footer>',
    '</div>';
}

//_______________________________________________________________
/**
* Affichages des résultats des SELECT des blablas.
*
* La fonction gére la boucle de lecture des résultats et les
* encapsule dans du code HTML envoyé au navigateur 
*
* @param mysqli_result  $r       Objet permettant l'accès aux résultats de la requête SELECT
*/
function hr_aff_blablas(mysqli_result $r): void {
    while ($t = mysqli_fetch_assoc($r)) {
        if ($t['oriID'] === null){
            $id_orig = $t['autID'];
            $pseudo_orig = $t['autPseudo'];
            $photo = $t['autPhoto'];
            $nom_orig = $t['autNom'];
        }
        else{
            $id_orig = $t['oriID'];
            $pseudo_orig = $t['oriPseudo'];
            $photo = $t['oriPhoto'];
            $nom_orig = $t['oriNom'];
        }
        echo    '<li>', 
                    '<img src="../', ($photo == 1 ? "upload/$id_orig.jpg" : 'images/anonyme.jpg'), 
                    '" class="imgAuteur" alt="photo de l\'auteur">',
                    hr_html_a('utilisateur.php', '<strong>'.hr_html_proteger_sortie($pseudo_orig).'</strong>','id', $id_orig, 'Voir mes infos'), 
                    ' ', hr_html_proteger_sortie($nom_orig),
                    ($t['oriID'] !== null ? ', recuité par '
                                            .hr_html_a( 'utilisateur.php','<strong>'.hr_html_proteger_sortie($t['autPseudo']).'</strong>',
                                                        'id', $t['autID'], 'Voir mes infos') : ''),
                    '<br>',
                    hr_html_proteger_sortie($t['blTexte']),
                    '<p class="finMessage">',
                    hr_amj_clair($t['blDate']), ' à ', hr_heure_clair($t['blHeure']),
                    '<a href="../index.html">Répondre</a> <a href="../index.html">Recuiter</a></p>',
                '</li>';
    }
}
//_______________________________________________________________
/**
* Affichages des résultats des SELECT des blablas.
*
* La fonction gére la boucle de lecture des résultats et les
* encapsule dans du code HTML envoyé au navigateur 
*
* @param mysqli_result  $r       Objet permettant l'accès aux résultats de la requête SELECT
*/
function rc_aff_blablasV2(mysqli_result $r, ?string $id=null): void {
    while ($t = mysqli_fetch_assoc($r)) {
        if ($t['oriID'] === null){
            $id_orig = $t['autID'];
            $pseudo_orig = $t['autPseudo'];
            $photo = $t['autPhoto'];
            $nom_orig = $t['autNom'];
        } else{
            $id_orig = $t['oriID'];
            $pseudo_orig = $t['oriPseudo'];
            $photo = $t['oriPhoto'];
            $nom_orig = $t['oriNom'];
        }
        $blablas_id = $t['blID'];


        $bd = hr_bd_connect();

        /**
         * GESTION DES LIENS MENTIONS
         */
        preg_match_all('/(@[[:alnum:]]{'.LMIN_PSEUDO.','.LMAX_PSEUDO.'})*/', $t['blTexte'], $matches);
        $arrMentions = array();
        for($i = 0; $i < count($matches[0]); $i++){
            if($matches[0][$i] !== ''){
                $pseudo = substr($matches[0][$i],1);

                $sql = "SELECT usID,usPseudo FROM users WHERE usPseudo='".$pseudo."'";
                $res = hr_bd_send_request($bd,$sql);
                $mentionned = mysqli_fetch_assoc($res);
                $stringLink = '@'.hr_html_a('utilisateur.php',''.hr_html_proteger_sortie($mentionned['usPseudo']).'','id',$mentionned['usID']);
                
                array_push($arrMentions,$stringLink);
            }else{
                array_push($arrMentions,'');
            }
        }
        if(count($arrMentions) > 0){
            $string = str_replace($matches[0],$arrMentions,$t['blTexte']);
        }

        /**
         * GESTION DES LIENS TAGS
         */
        preg_match_all('/(#[[:alnum:]]{1,60})*/', $t['blTexte'], $matches);
        $arrTags = array();
        for($i = 0; $i < count($matches[0]); $i++){
            if($matches[0][$i] !== ''){
                $tagID = substr($matches[0][$i],1);

                $sql = "SELECT taID FROM tags WHERE taID='".$tagID."'";
                $res = hr_bd_send_request($bd,$sql);
                $tag = mysqli_fetch_assoc($res);
                $tagLink = '#'.hr_html_a('tendances.php',''.hr_html_proteger_sortie($tag['taID']).'','tag',$tag['taID']);
                
                array_push($arrTags,$tagLink);
            }else{
                array_push($arrTags,'');
            }
        }
        if(count($arrTags) > 0){
            $string = str_replace($matches[0],$arrTags,$string);
        }
        
        


        echo    '<li>', 
                    '<img src="../', ($photo == 1 ? "upload/$id_orig.jpg" : 'images/anonyme.jpg'), 
                    '" class="imgAuteur" alt="photo de l\'auteur">',
                    hr_html_a('utilisateur.php', '<strong>'.hr_html_proteger_sortie($pseudo_orig).'</strong>','id', $id_orig, 'Voir mes infos'), 
                    ' ', hr_html_proteger_sortie($nom_orig),
                    ($t['oriID'] !== null ? ', recuité par '
                                            .hr_html_a( 'utilisateur.php','<strong>'.hr_html_proteger_sortie($t['autPseudo']).'</strong>',
                                                        'id', $t['autID'], 'Voir mes infos') : ''),
                    '<br>';
                    
                    echo $string,
                    '<p class="finMessage">',
                    hr_amj_clair($t['blDate']), ' à ', hr_heure_clair($t['blHeure']),
                    ($id !== $id_orig ? "<a href='./cuiteur.php?answer=true&auteur=$pseudo_orig&id=$id_orig'>Répondre</a> <a href='./cuiteur.php?recuit=true&aut=$id_orig&blID=$blablas_id'>Recuiter</a>" : 
                    "<a href='./cuiteur.php?delete=true&blID=$blablas_id'>Supprimer</a>"),
                    '</p>',
                '</li>';
    }
}

//_______________________________________________________________
/**
* Détermine si l'utilisateur est authentifié
*
* @global array    $_SESSION 
* @return bool     true si l'utilisateur est authentifié, false sinon
*/
function hr_est_authentifie(): bool {
    return  isset($_SESSION['usID']);
}

//_______________________________________________________________
/**
 * Termine une session et effectue une redirection vers la page transmise en paramètre
 *
 * Elle utilise :
 *   -   la fonction session_destroy() qui détruit la session existante
 *   -   la fonction session_unset() qui efface toutes les variables de session
 * Elle supprime également le cookie de session
 *
 * Cette fonction est appelée quand l'utilisateur se déconnecte "normalement" et quand une 
 * tentative de piratage est détectée. On pourrait améliorer l'application en différenciant ces
 * 2 situations. Et en cas de tentative de piratage, on pourrait faire des traitements pour 
 * stocker par exemple l'adresse IP, etc.
 * 
 * @param string    URL de la page vers laquelle l'utilisateur est redirigé
 */
function hr_session_exit(string $page = '../index.php'):void {
    session_destroy();
    session_unset();
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), 
            '', 
            time() - 86400,
            $cookieParams['path'], 
            $cookieParams['domain'],
            $cookieParams['secure'],
            $cookieParams['httponly']
        );
    header("Location: $page");
    exit();
}
/**
* AFFICHER FORM INFORMATIONS PERSONNELLES
* @param $errs tableaux d'erreur du formulaire
*/
function hr_aff_info_perso($bd,array $errs):void{
    if(isset($_POST['btnValiderPerso'])){
        if(count($errs) > 0){
            echo '<p class="error">Les erreurs suivantes ont été détectées :';
            foreach ($errs as $v) {
                echo '<br> - ', $v;
            }
            echo '</p>';    
        }
        if(count($errs) === 0){
            echo '<p class="success">La mise à jour des informations sur votre compte a bien été effectuée</p>'; 

            list($annee, $mois, $jour) = explode('-', $_POST['naissance']);
            $aaaammjj = $annee*10000  + $mois*100 + $jour;

            $nomprenom = hr_bd_proteger_entree($bd, $_POST['nomprenom']);

            $ville = hr_bd_proteger_entree($bd,$_POST['ville']);

            $bio = hr_bd_proteger_entree($bd,$_POST['bio']);

            $sql = "UPDATE users SET usNom='$nomprenom',usVille='$ville',usBio='$bio',usDateNaissance=$aaaammjj  WHERE usID = '".$_SESSION['usID']."'";
            hr_bd_send_request($bd,$sql);
        }
    }

    $sql = "SELECT * FROM users WHERE usID = '".$_SESSION['usID']."'";
    $res = hr_bd_send_request($bd,$sql);
    $t = mysqli_fetch_assoc($res);

    $dateChange = hr_amj_date($t['usDateNaissance']);
    $dateChange = strtr($dateChange,'/','-');
    $date = date('Y-m-d',strtotime(hr_html_proteger_sortie($dateChange)));

    echo '<form method="post" action="compte.php">',
                    '<table>';
                    hr_aff_ligne_input('Nom', array('type' => 'text', 'name' => 'nomprenom', 'value' => hr_html_proteger_sortie($t['usNom']), 'required' => null));
                    hr_aff_ligne_input('Date de naissance', array('type' => 'date', 'name' => 'naissance', 'value' => $date, 'required' => null));
hr_aff_ligne_input('Ville', array('type' => 'text', 'name' => 'ville', 'value' => hr_html_proteger_sortie($t['usVille'])));
    echo '<tr class="containerBio">',
            '<td ><label for="bio">Mini-bio</label></td>',
            '<td><textarea id="bio" name="bio" rows="13" cols="35">',hr_html_proteger_sortie($t['usBio']),'</textarea></td>',
        '</tr>';
    echo '<tr>',
            '<td colspan="2">',
                '<input type="submit" name="btnValiderPerso" value="Valider">',
            '</td>',
        '</tr>',
    '</table>',
    '</form>';
}

/**
* AFFICHER FORM INFORMATIONS COMPTE CUITEUR
* @param $errs tableaux d'erreur du formulaire
*/
function hr_aff_info_account($bd,array $errs):void{
    if(isset($_POST['btnValiderAccount'])){
        if(count($errs) > 0){
            echo '<p class="error">Les erreurs suivantes ont été détectées :';
            foreach ($errs as $v) {
                echo '<br> - ', $v;
            }
            echo '</p>';
        }
        if(count($errs) === 0){
            echo '<p class="success">La mise à jour des informations sur votre compte a bien été effectuée</p>'; 

            $mail = hr_bd_proteger_entree($bd, $_POST['mail']);

            $site = hr_bd_proteger_entree($bd, $_POST['site']);
            
            $sql = "UPDATE users SET usMail='$mail',usWeb='$site' WHERE usID = '".$_SESSION['usID']."'";
            hr_bd_send_request($bd,$sql);
        }
    }

    $sql = "SELECT * FROM users WHERE usID = '".$_SESSION['usID']."'";
    $res = hr_bd_send_request($bd,$sql);
    $t = mysqli_fetch_assoc($res);

    echo '<form method="post" action="compte.php">',
                    '<table>';
                    hr_aff_ligne_input('Adresse mail', array('type' => 'text', 'name' => 'mail', 'value' => hr_html_proteger_sortie($t['usMail']), 'required' => true));
                    hr_aff_ligne_input('Site web', array('type' => 'text', 'name' => 'site', 'value' => hr_html_proteger_sortie($t['usWeb'])));
    echo '<tr>',
            '<td colspan="2">',
                '<input type="submit" name="btnValiderAccount" value="Valider">',
            '</td>',
        '</tr>',
    '</table>',
    '</form>';
}

/**
* AFFICHER FORM PARAMETRES COMPTE CUITEUR
* @param $errs tableaux d'erreur du formulaire
*/
function hr_aff_settings_account($bd,array $errs):void{

    if(isset($_POST['btnValiderSettings'])){
        if(count($errs) > 0){
            echo '<p class="error">Les erreurs suivantes ont été détectées :';
            foreach ($errs as $v) {
                echo '<br> - ', $v;
            }
            echo '</p>';
        }
        if(count($errs) === 0){
            echo '<p class="success">La mise à jour des informations sur votre compte a bien été effectuée</p>';
            if(isset($_FILES['image']) && isset($_POST['usePhoto'])){
                $temp = explode(".", $_FILES['image']['name']);
                $newfilename = $_SESSION['usID']. '.' . end($temp);
                move_uploaded_file($_FILES['image']['tmp_name'],'../upload/'.$newfilename);
                if(file_exists('../upload/'.$_SESSION['usID'].'.jpg')){
                    $sql = "UPDATE users SET usAvecPhoto = 1 WHERE usID = ".$_SESSION['usID']."";
                    hr_bd_send_request($bd,$sql);
                }else{
                    $sql = "UPDATE users SET usAvecPhoto = 0 WHERE usID = ".$_SESSION['usID']."";
                    hr_bd_send_request($bd,$sql);
                }
            }

            if($_POST['passe1'] !== '' && $_POST['passe2'] !== ''){
                $password = hr_bd_proteger_entree($bd, password_hash($_POST['passe1'],PASSWORD_DEFAULT));
                $sql = "UPDATE users SET usPasse='$password' WHERE usID = '".$_SESSION['usID']."'";
                hr_bd_send_request($bd,$sql);
            }

        }
    }
    $sql = "SELECT * FROM users WHERE usID = '".$_SESSION['usID']."'";
    $res = hr_bd_send_request($bd,$sql);
    $t = mysqli_fetch_assoc($res);

    echo '<form method="post" action="compte.php" enctype="multipart/form-data">',
                    '<table>';
                    hr_aff_ligne_input('Changer le mot de passe : ', array('type' => 'password', 'name' => 'passe1', 'value' => NULL, null));
                    hr_aff_ligne_input('Répétez le mot de passe : ', array('type' => 'password', 'name' => 'passe2', 'value' => NULL, null));

    if($t['usAvecPhoto'] == 1 && file_exists('../upload/'.$_SESSION['usID'].'.jpg')){
        $img = '<img src="../upload/'.$_SESSION['usID'].'.jpg" alt="'.$_SESSION['usID'].'.jpg" width="50px" height="50px">';
    }else{
        $img = '<img src="../images/anonyme.jpg" alt="Default_User_Image" width="50px" height="50px">';
    }
    echo '<tr>',
        '<td><label for=""> Votre photo actuelle </label></td>',
        '<td>',
            $img,
            '<p>Taille 20ko maximum</p>',
            '<p>Image JPG carrée (mini 50x50px)</p>',
            '<input type="file" name="image">',
        '</td>',
    '</tr>'; 
       
    echo    '<tr>',
            '<td>','<label for="">','Utiliser votre photo','</label>','</td>',
            '<td>',
                '<input type="radio" name="dontUsePhoto" value=1><label for="non">non</label>',
                '<input type="radio" name="usePhoto"><label for="oui">oui</label>',
            '</td>',
        '</tr>';
    echo '<tr>',
            '<td colspan="2">',
                '<input type="submit" name="btnValiderSettings" value="Valider">',
            '</td>',
        '</tr>',
    '</table>',
    '</form>';
}

/**
 * Supprime un blabla
 * 
 * @param myslqi $bd Objet permettant la communication avec la base de donnee
 * @param int  $blID identifiant du blabla a supprimer
 */
function rc_delete_blablas(mysqli $bd,int $blID): void{
    $sql =  "DELETE FROM tags WHERE taIDBlabla=$blID;";
    $sql1 =  "DELETE FROM mentions WHERE meIDBlabla=$blID;";
    $sql2 =  "DELETE FROM blablas WHERE blID=$blID;";
            
    $requet = hr_bd_send_request($bd,$sql);
    $requet = hr_bd_send_request($bd,$sql1);
    $requet = hr_bd_send_request($bd,$sql2);
}
/**
 * Gestion de l'affichage du bouton "plus de Blabblas"
 * Si il reste encore des blablas a afficher alors le bouton est afficher 
 * 
 * @param int $nb_blablas le nombre de blablas deja affichees
 * @param string $adresse chaine de la page pointe par le lien
 * @param string $nb_blablas_total le nombre de blablas total recupere
 * @param int $id l'identifiant de l'utillsateur concerne
 */
function rc_aff_more_blablas(int $nb_blablas,string $address,int $nb_blablas_total,?int $id=null): void {
    if($nb_blablas<=$nb_blablas_total){
        $nb_blablas += 4;
        echo    "<li class='plusBlablas'>
                    <a href='./$address?more=true&nb=$nb_blablas&id=$id' ><strong>Plus de blablas</strong></a>
                    <img src='../images/speaker.png' width='75' height='82' alt='Image du speaker '",'Plus de blablas',"'>
            </li>";
    }
    echo '</ul>';
}

function hr_aff_more_blablas_tendance(int $nb_blablas,string $address,int $nb_blablas_total,?string $tag=null): void {
    if($nb_blablas<$nb_blablas_total){
        $nb_blablas += 4;
        echo    "<li class='plusBlablas'>
                    <a href='./$address?more=true&nb=$nb_blablas&tag=$tag' ><strong>Plus de blablas</strong></a>
                    <img src='../images/speaker.png' width='75' height='82' alt='Image du speaker '",'Plus de blablas',"'>
            </li>";
    }
    echo '</ul>';
}

/**
 * Permet l'affichage et la mise en forme d'un titre d'une section de la page
 */
function rc_aff_titre_section(string $titre):void{
    echo '<h2 class="titre">',$titre,'</h2>';
}

/**
 * Affiche la barre de recherche et les erreurs détectés
 * 
 * @param array $err le tableau d'erreurs detectes
 */
function rcl_aff_recherche(array $err): void{
    if(isset($_POST['btnRecherche'])){
        $value = hr_html_proteger_sortie($_POST['recherche']);
    }else{
        $value = '';
    }
    if(count($err)>0){
        echo '<p class="error">Les erreurs suivantes ont été détectées :';
        foreach ($err as $v) {
            echo '<br> - ', $v;
        }
        echo '</p>';
    }
    echo    '<form method="post" action="recherche.php">
                <input id="textrecherche" type="text" name="recherche" value=',$value,' >
                <input type="submit" name="btnRecherche" value="Rechercher">
            </form>';
    
}

/**
 * Affiche les infos d'un utilisateur
 */
function hr_aff_user(mysqli $bd, ?int $id, ?array $info_id=array(), ?bool $sub=TRUE, ?bool $is_user=TRUE):void{
    //GET COUNT BLABLAS
    $sql = "SELECT COUNT(*) AS NB FROM blablas WHERE blIDAuteur = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tBla = mysqli_fetch_assoc($res);
    //GET COUNT MENTIONS
    $sql = "SELECT COUNT(*) AS NB FROM mentions WHERE meIDUser = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tMen = mysqli_fetch_assoc($res);
    //GET COUNT ABONNES
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDAbonne = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tFol = mysqli_fetch_assoc($res);
    //GET COUNT ABONNEMENTS
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDUser = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tSub = mysqli_fetch_assoc($res);

    if($info_id['usAvecPhoto'] == 1 && file_exists('../upload/'.$id.'.jpg')){
        $img = '<img id="ppUsr" src="../upload/'.$id.'.jpg" alt="photo de l\'utilisateur">';
    }else{
        $img = '<img id="ppUsr" src="../images/anonyme.jpg" alt="photo de l\'utilisateur">';
    }

    echo '<div class="profil">';
    echo $img,
    '<span>',
        hr_html_a('utilisateur.php',hr_html_proteger_sortie($info_id['usPseudo']),'id',$id,'Voir les infos de l\'utilisateur').' '.hr_html_proteger_sortie($info_id['usNom']).'<br>',
        hr_html_a('blablas.php',hr_html_proteger_sortie($tBla['NB']).' blablas','id',$id,'Voir les blablas de l\'utilisateur'),
        ' - ',
        hr_html_a('mentions.php',hr_html_proteger_sortie($tMen['NB']).' mentions','id',$id,'Voir les mentions de l\'utilisateur'),
        ' - ',
        hr_html_a('abonnes.php',hr_html_proteger_sortie($tFol['NB']).' abonnés','id',$id,'Voir les abonnés de l\'utilisateur'),
        ' - ',
        hr_html_a('abonnements.php',hr_html_proteger_sortie($tSub['NB']).' abonnements','id',$id,'Voir les abonnements de l\'utilisateur'),
    '</span>';


    if(!$is_user){
        if($sub){
            echo '<p class="finMessageAbo">',
            '<input type="checkbox" name="unsub'.$id.'"><label><strong>Se désabonner</strong></label>',
            '</p>';
        }else{
            echo '<p class="finMessageAbo">',
            '<input type="checkbox" name="sub'.$id.'"><label><strong>S\'abonner</strong></label>',
            '</p>';
        } 
    }
    echo '</div>';  
}

/**
 * AFFICHE USER 
 */

function rc_aff_user(int $nbBlablas, int $nbMentions, int $nbAbonnee, int $nbAbonnement,string $pseudo, int $photo, int $id, string $nom ): void{

    if($photo == 1 && file_exists('../upload/'.$id.'.jpg')){
        $img = '<img class="imgAuteur" src="../upload/'.$id.'.jpg" alt="'.$id.'.jpg" width="50px" height="50px">';
    }else{
        $img = '<img class="imgAuteur" src="../images/anonyme.jpg" alt="Default_User_Image" width="50px" height="50px">';
    }
    //echo '<li class="liAbonnement">', 
    echo $img,
    hr_html_a('utilisateur.php', '<strong>'.hr_html_proteger_sortie($pseudo).'</strong>','id', $id, 'Voir mes infos'), 
    ' ', hr_html_proteger_sortie($nom),
    '<br>',
    hr_html_a('blablas.php','<strong>'.hr_html_proteger_sortie($nbBlablas).' blablas</strong>','id',$id,'Voir les blablas' ),
    ' - ',
    hr_html_a('mentions.php','<strong>'.hr_html_proteger_sortie($nbMentions).' mentions</strong>','id',$id,'Voir les mentions' ),
    ' - ',
    hr_html_a('abonnes.php','<strong>'.hr_html_proteger_sortie($nbAbonnee).' abonnées</strong>','id',$id,'Voir les abonnées' ),
    ' - ',
    hr_html_a('abonnements.php','<strong>'.hr_html_proteger_sortie($nbAbonnement).' abonnements</strong>','id',$id,'Voir les abonnements' );
}

/**
 * AFFICHER LES ABONNES/ABONNEMENTS D'UN USER
 */
function hr_aff_abo(mysqli $bd,int $id): void {
    //RECUPERE INFO USER $id
    $sql = "SELECT * FROM users WHERE usID = $id";
    $r = hr_bd_send_request($bd,$sql);
    $t = mysqli_fetch_assoc($r);
    //GET COUNT BLABLAS
    $sql = "SELECT COUNT(*) AS NB FROM blablas WHERE blIDAuteur = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tBla = mysqli_fetch_assoc($res);
    //GET COUNT MENTIONS
    $sql = "SELECT COUNT(*) AS NB FROM mentions WHERE meIDUser = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tMen = mysqli_fetch_assoc($res);
    //GET COUNT ABONNES
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDAbonne = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tFol = mysqli_fetch_assoc($res);
    //GET COUNT ABONNEMENTS
    $sql = "SELECT COUNT(*) AS NB FROM estabonne WHERE eaIDUser = $id";
    $res = hr_bd_send_request($bd,$sql);
    $tSub = mysqli_fetch_assoc($res);

    $id = $t['usID'];
    $pseudo = $t['usPseudo'];
    $photo = $t['usAvecPhoto'];
    $nom = $t['usNom'];

    if($t['usAvecPhoto'] == 1 && file_exists('../upload/'.$id.'.jpg')){
        $img = '<img class="imgAuteur" src="../upload/'.$id.'.jpg" alt="'.$id.'.jpg" width="50px" height="50px">';
    }else{
        $img = '<img class="imgAuteur" src="../images/anonyme.jpg" alt="Default_User_Image" width="50px" height="50px">';
    }
    echo '<li class="liAbonnement">', 
    $img,
    hr_html_a('utilisateur.php', '<strong>'.hr_html_proteger_sortie($pseudo).'</strong>','id', $id, 'Voir mes infos'), 
    ' ', hr_html_proteger_sortie($nom),
    '<br>',
    hr_html_a('blablas.php',hr_html_proteger_sortie($tBla['NB']).' blablas','id',$id,'Voir les blablas de l\'utilisateur'),
    ' - ',
    hr_html_a('mentions.php',hr_html_proteger_sortie($tMen['NB']).' mentions','id',$id,'Voir les mentions de l\'utilisateur'),
    ' - ',
    hr_html_a('abonnes.php',hr_html_proteger_sortie($tFol['NB']).' abonnés','id',$id,'Voir les abonnés de l\'utilisateur'),
    ' - ',
    hr_html_a('abonnements.php',hr_html_proteger_sortie($tSub['NB']).' abonnements','id',$id,'Voir les abonnements de l\'utilisateur');


    
    //CHECK IF USER IS SUBSCRIBED TO $id
    $sql = "SELECT * FROM estabonne WHERE eaIDUser = ".$_SESSION['usID']." AND eaIDAbonne = $id";
    $res = hr_bd_send_request($bd,$sql);
    $estAbonne = mysqli_num_rows($res);

    if($id !== $_SESSION['usID']){
        echo '<p class="finMessageAbo">',
        '<input type="checkbox" name="'.$id.'"value="'.($estAbonne===0 ? 'abonne' : 'desabonne').'">',
        '<label for="abonne"><strong>',($estAbonne===0 ? 's\'abonner' : 'se désabonner'),'</strong></label>';
    }else{
        echo '<p class="finMessageAbo">',
            '<br>',
            '</p>';
    }
    echo '</li>';
}

/**
 * permet l'affichage et la mise en forme d'un message en cas d'une recherche ou suggestion sans resultat 
 */
function rc_aff_res(string $resultat): void{
    echo "<p class='resultat'>$resultat<p>";
}

/**
 * Gere l'abonnement ou le desabonnement a un utilisateur par l'utilisateur courant
 * les utilisateur auquels l'utilisateur s'abonne ou se desabonne sont recupere dans $_POST
 * 
 * @param int $id l'identifiant de l'utilisateur courant
 * @param mysqli Objet permettant la communication avec la base de donnee
 */
function rc_gestion_sub_unsub(int $id, mysqli $bd): void {
    $date = $today = date('Ymd');
    $insert = "INSERT INTO estabonne(eaIDUser,eaIDAbonne,eaDate) VALUE ";   
    $delete = "DELETE from estabonne WHERE eaIDUser=$id AND  eaIDAbonne IN (";
    $sub = array();
    $unsub = array();
    foreach ($_POST as $c => $v) {
        if($c !== 'btnValider'){
            echo '<br>'.$v;
            ($v === 'abonne' ? array_push($sub,$c) : array_push($unsub,$c));
        }
    }
    echo '<br>';print_r($sub);
    $size = count($sub);
    if($size > 0){
        for($i = 0 ; $i<$size; $i++){
            ($i === $size-1 ? $insert .= "($id,$sub[$i],$today)" : $insert .= "($id,$sub[$i],$today),");    
        }
        hr_bd_send_request($bd,$insert);
    }
    echo '<br>';print_r($unsub);
    $size = count($unsub);
    if($size > 0){
        for($i = 0 ; $i<$size; $i++){
            ($i === $size-1 ? $delete .= "$unsub[$i]" :  $delete .= "$unsub[$i]," );
        }
        $delete.=')';
        hr_bd_send_request($bd,$delete);
    }
    header('Location:cuiteur.php');
    exit();
}
/**
 * Permet l'affichage d'une tendance pour une periode donne 
 * 
 * @param mysqli $bd objet permettant la communication avec la base de donnée
 * @param string $titre le titre de la tendances 
 * @param string $first_date la date de debut de periode
 * @param stiring $second_date la date de fin de period (souvent la date d'aujourd'hui), elle peut etre null
 */
function rc_aff_tend(mysqli $bd,string $titre, string $first_date, ?string $second_date=NULL): void{
    echo "<h3>$titre</h3>";
    if($second_date === null){
        $sql = "SELECT taID, COUNT(*) AS NB  FROM tags,blablas WHERE taIDBlabla = blID AND blDate=$first_date
            GROUP BY taID
            ORDER BY NB DESC
            LIMIT 10";
    }else{
        $sql = "SELECT taID, COUNT(*) AS NB  FROM tags,blablas WHERE taIDBlabla = blID AND blDate<=$first_date AND blDate>=$second_date
        GROUP BY taID
        LIMIT 10;";
    }
    $request = hr_bd_send_request($bd,$sql);
    if(mysqli_num_rows($request)!=0){
        echo '<ol>';
        while($t = mysqli_fetch_assoc($request)){
            echo '<li>',hr_html_a('tendances.php','<strong>'.hr_html_proteger_sortie($t['taID']).' ('.$t['NB'].')</strong>' ,'tag',$t['taID'],'Voir les blablas du tag'),'</li>';
        }
        echo '</ol >';
    }else{
        echo '<p>Aucune tendance ...</p>';
    }

}
?>
