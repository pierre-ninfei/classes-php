<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Infos sur les classes (version PDO)</title>
</head>

	<header>
		<h1>Informations sur les requêtes PDO.</h1>
	</header>

	<body>

		<form method="post">
			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="enregistrer" value="Enregistrer un nouvel utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="connexion" value="Connectez l'utilisateur"> &nbsp; &nbsp;

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="déconnexion" value="Déconnectez l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="supprimer" value="Supprimez l'utilisateur"> &nbsp; &nbsp;

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="modifier" value="Modifiez l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="connected" value="Vérifiez la connexion"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="allinfos" value="Vérifiez les Informations sur l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="getlogin" value="Vérifiez le login de l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="getemail" value="Vérifiez l'adresse email de l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="getfirstname" value="Vérifiez le Firstname de l'utilisateur"><br/><br/>

			<input style="border: 1px solid black; background-color: lightgreen;" type="submit" name="getlastname" value="Vérifiez le Lastname de l'utilisateur"><br/><br/>
		</form>

		<?php 

		session_start();

		class User{
			private $id;
			public $login;
			public $email;
			public $password;
			public $firstname;
			public $lastname;
			public $bdd;


			public function __construct(){
				$dsn = "mysql:host=localhost;dbname=classes;charset=UTF8";

				try{
					$this->bdd = new PDO($dsn, 'root', '');
				}
				catch (PDOException $e){
					echo $e->getMessage();
				}
			}

			function register($login, $email, $password, $firstname, $lastname){
				if(isset($_POST['enregistrer'])){
					$rquery = $this->bdd->prepare("INSERT INTO utilisateurs(login, password, email, firstname, lastname) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')");
					$rquery->execute();
					$rdata = $rquery->fetchAll();
					echo "<table style='text-align:center;'><th colspan='5'><b> Nouvel utilisateur enregistré : </b></br></th>
					<tr><td> login </td><td> email </td><td> password </td><td> firstname </td><td> lastname </td></tr>
					<tr><td>". $login. "</td><td>". $email. "</td><td>". $password. "</td><td>". $firstname. "</td><td>". $lastname. "</td><td>	</tr>";
				}
			}

			public function connect($login, $password){
				if(isset($_POST['connexion'])){
					$cquery = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE login = '$login' && password = '$password'");
					$cquery->execute();
					$cdata = $cquery->fetchAll();
					$counter = count($cdata);

					if($counter != 0){
						$_SESSION['userI'] = $cdata[0];
						echo "Bon retour parmis nous, ". $_SESSION['userI']['login']. ".";
						$this->login = $_SESSION['userI']['login'];
	                    $this->password = $_SESSION['userI']['password'];
	                    $this->email = $_SESSION['userI']['email'];
	                    $this->firstname = $_SESSION['userI']['firstname'];
	                    $this->lastname = $_SESSION['userI']['lastname'];
					}
               		else{
               			echo " utilisateur invalide";
               		}
				}
			}

			function disconnect(){
				if(isset($_POST['déconnexion'])){
						session_destroy();
						unset($_SESSION['userI']);
						echo "utilisateur déconnecté";
					}
				}

			public function delete(){
				if(isset($_POST['supprimer'])){
					$this->login = $_SESSION['userI']['login'];
					$dquery = $this->bdd->prepare("DELETE FROM utilisateurs WHERE login = '$this->login'");
					$dquery->execute();
					echo "utilisateur supprimé";
					session_destroy();
					unset($_SESSION['userI']);	
				}
			}

			function update($login, $email, $password, $firstname, $lastname){
				if(isset($_POST['modifier'])){
					$this->id = $_SESSION['userI']['id'];
		            $this->login = $login;
		            $this->password = $password;
		            $this->email = $email;
		            $this->firstname = $firstname;
		            $this->lastname = $lastname;
					$uqery = $this->bdd->prepare("UPDATE utilisateurs SET login = '$this->login', password = '$this->password', email = '$this->email', firstname = '$this->firstname', lastname = '$this->lastname' WHERE id= '$this->id'");
					$uqery->execute();
					echo "Modifications validées";
				}
			}

			function isConnected(){
				if(isset($_POST['connected'])){
					if(isset($_SESSION['userI'])){
						echo "l'utilisateur est connecté";
					}
					else{
						echo "il n'y a pas d'utilisateur connecté";
					}
				}
			}

			function getAllInfos(){
				if(isset($_POST['allinfos'])){
					$this->id = $_SESSION['userI']['id'];
					$iquery = $this->bdd->prepare("SELECT login, password, email, firstname, lastname FROM utilisateurs WHERE id = $this->id");
					$iquery->execute();
					$idatas = $iquery->fetchAll();
					
					echo " <b>Voici les informations sur l'utilisateur :</b><br/><br/> <table><tr>";
					foreach($idatas as $idata){
						for($i=0; $i<=4; $i++){
							echo"$idata[$i] <br/>";
						}
					}
					echo "<br/>";
				}
			}

			function getLogin(){
				if(isset($_POST['getlogin'])){
					$this->id = $_SESSION['userI']['id'];
					$lquery = $this->bdd->prepare("SELECT login FROM utilisateurs WHERE id = '$this->id'");
					$lquery->execute();
					$ldata = $lquery->fetchAll();
					$login = $ldata[0]['login'];
							echo " le login de l'utilisateur est <b> $login </b>";
				}
			}

			function getEmail(){
				if(isset($_POST['getemail'])){
					$this->id = $_SESSION['userI']['id'];
					$equery = $this->bdd->prepare("SELECT email FROM utilisateurs WHERE id = '$this->id'");
					$equery->execute();
					$edata = $equery->fetchAll();
					$email = $edata[0]['email'];
						echo " l'email de l'utilisateur est <b> $email </b>";	
				}
			}

			function getFirstname(){
				if(isset($_POST['getfirstname'])){
					$this->id = $_SESSION['userI']['id'];
					$fquery = $this->bdd->prepare("SELECT firstname FROM utilisateurs WHERE id = '$this->id'");
					$fquery->execute();
					$fdata = $fquery->fetchAll();
					$firstname = $fdata[0]['firstname'];
						echo " le Firstname de l'utilisateur est <b> $firstname </b>";	
				}
			}

			function getLastname(){
				if(isset($_POST['getlastname'])){
					$this->id = $_SESSION['userI']['id'];
					$laquery = $this->bdd->prepare("SELECT lastname FROM utilisateurs WHERE id = '$this->id'");
					$laquery->execute();
					$ladata = $laquery->fetchAll();
					$lastname = $ladata[0]['lastname'];
						echo " le Lastname de l'utilisateur est <b> $lastname </b>";	
				}
			}
		}

		$user1 = new User();

		$user1->register("patrickus","patest@gmail.com","test123","patrick","testus");
		$user1->connect("patrickus","test123");
		$user1->disconnect();
		$user1->delete();
		$user1->update("patrix","patrix@gmail.com","test124","patrix","testos");
		$user1->isConnected();
		$user1->getAllInfos();
		$user1->getLogin();
		$user1->getEmail();
		$user1->getFirstname();
		$user1->getLastname();

		?>
		
	</body>

	<footer>
		<h1 style="color: white;"> Ceci est un footer :)</h1>
	</footer>

</html>