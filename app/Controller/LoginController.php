<?php 

namespace Controller;

use \W\Controller\Controller;
use \W\Security\AuthentificationModel;
use \W\Model\UsersModel;

class LoginController extends Controller {

	public function form() {

		// Si un post à bien été envoyé, on effectue le traitement du formulaire

		// Je crée un tableau d'erreur
		$errors = array();

		// var_dump('Contenu de $_POST: ',$_POST);

		if($_POST){
			// On vérifie qu'on à bien entré des champs dans le formulaire.
			if(empty($_POST['pseudo'])){
				$errors['pseudo'] = 'Vous devez renseigner un pseudo';
			}

			if(empty($_POST['mot_de_passe'])) {
				$errors['mot_de_passe'] = 'Vous devez renseigner un mot de passe';
			}

			// var_dump('Contenu de mes erreurs après vérification empty()', $errors);

			// Je fais appel au modèle d'authentification de façon à profiter de la méthode isValidLoginInfo qui à été codé par les concepteurs du framework.
			$auth = new AuthentificationModel();
			// On fait appel à isValidLoginInfo qui va vérifier que la combinaison pseudo/mot de passe entré par l'utilisateur correspond bien à un utilisateur en base de données.
			$pseudo = !empty($_POST['pseudo']) ? $_POST['pseudo'] : '';
			$motDePasse = !empty($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

			// var_dump('Pseudo: ', $pseudo);
			// var_dump('Mot de passe: ', $motDePasse);

			$userId = $auth -> isValidLoginInfo($_POST['pseudo'], $_POST['mot_de_passe']);

			// var_dump('User Id: ', $userId);

			if($userId === 0) {
				$errors['pseudo/mdp'] = 'Les informations de connexion entrées sont incorrectes.';	
			}

			// var_dump('Contenu de mes erreurs après validation totale :', $errors);

			// Je vérifie que le tableau d'erreur est non vide, ce qui signifie que le formulaire à été correctement rempli
			if (empty($errors)) {
				$usersModel = new UsersModel();
				$userInfos = $usersModel -> find($userId);
				var_dump('Informations de l\'utilisateur', $userInfos);
			 	$auth -> logUserIn($userInfos);

			 	$this -> redirectToRoute('default_home');
			} 
		}
		$this -> show('login/form', array(
			'errors' => $errors
		));
	}
}
