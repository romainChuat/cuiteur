<?php 
/* ROUGETET HUGO --  CHUAT ROMAIN */

ob_start();
session_start();

require_once 'php/bibli_generale.php';
require_once 'php/bibli_cuiteur.php';
 
if(hr_est_authentifie()){
    header('Location:php/cuiteur.php');
    exit();
}

$err = isset($_POST['btnConnexion']) ? rc_traitement_connexion() : array(); 

hr_aff_debut('Cuiteur | Connexion','styles/cuiteur.css');
hr_aff_entete('Connectez-vous');
hr_aff_infos(false);

rc_aff_form_connexion($err);

hr_aff_pied();
hr_aff_fin();

ob_end_flush();

function rc_aff_form_connexion(array $err):void{
    if (isset($_POST['btnConnexion'])){
        $values = hr_html_proteger_sortie($_POST);
    }
    else{
        $values['pseudo'] = '';
    }
    if(count($err) > 0){
        echo '<p class="error">Mot de passe ou pseudo incorrecte</p>';
    }
        echo '<p>pour vous connecter à cuiteur, il faut vous authentifier</p>',
         '<form method="post" action="index.php">',
            '<table>';
                hr_aff_ligne_input( 'Pseudo :', array('type' => 'text', 'name' => 'pseudo','value' => $values['pseudo'], 'required' => null));
                hr_aff_ligne_input('Mot de passe :', array('type' => 'password','name' => 'passe', 'required' => null));
                echo '<tr>',
                '<td colspan="2">',
                    '<input type="submit" name="btnConnexion" value="Connexion">',
                '</td>',
                '</tr>';
            echo '</table>',
    
        '</form>';

        echo '<p>Pas encore de compte ? <a href="php/inscription.php">Inscrivez vous</a> sans tarder !</p>';
        echo '<p>Vous hésitez à vous inscrire ? Laissez vous séduire par une <a href="html/presentation.html">présentation</a> des possibilités de Cuiteur</p>';
}
function rc_traitement_connexion(): array{
    if(!hr_parametres_controle('post',array('pseudo','passe','btnConnexion'))){
        hr_session_exit();
    }
    foreach($_POST as &$val){
        $val = trim($val);
    }
    $err = array();    
    $bd = hr_bd_connect();
    $sql1 = "SELECT * FROM users WHERE usPseudo='".$_POST['pseudo']."'";
    $request1 = hr_bd_send_request($bd,$sql1); 
    

    if (mysqli_num_rows($request1) === 1 ) {
        $t = mysqli_fetch_assoc($request1);
        if(password_verify($_POST['passe'], $t['usPasse'])){
            $_SESSION['usID'] = $t['usID'];
            mysqli_close($bd);
            header('Location:php/cuiteur.php');
            exit();
        }
    }
    $err[] = 'Pseudo incorrect <br>';
    return $err;
}
?>
