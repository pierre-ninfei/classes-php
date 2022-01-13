<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Infos sur les classes</title>
</head>

	<header>
		<h1>Informations sur les requêtes Mysqli.</h1>
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
				$this->bdd = mysqli_connect('localhost', 'root', '', 'classes') OR die("Impossible de se connecter à la database");
			}

			// definition des méthodes

			function register($login, $email, $password, $firstname, $lastname){
				if(isset($_POST['enregistrer'])){
					$rquery = mysqli_query($this->bdd, "INSERT INTO utilisateurs(login, password, email, firstname, lastname) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')");
					echo "<table style='text-align:center;'><th colspan='5'><b> Nouvel utilisateur enregistré : </b></br></th>
					<tr><td> login </td><td> email </td><td> password </td><td> firstname </td><td> lastname </td></tr>
					<tr><td>". $login. "</td><td>". $email. "</td><td>". $password. "</td><td>". $firstname. "</td><td>". $lastname. "</td><td>	</tr>";
				}
			}

			public function connect($login, $password){
				if(isset($_POST['connexion'])){
					$cquery = mysqli_query($this->bdd, "SELECT * FROM utilisateurs WHERE login = '$login' && password = '$password'");
					$req = mysqli_fetch_assoc($cquery);
					$counter = count($req);

					// si il n'y a pas d'erreur, user logs in
					if($counter != 0){
						$_SESSION['userI'] = $req;
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
					$dquery = mysqli_query($this->bdd,"DELETE FROM utilisateurs WHERE login = '$this->login'");
					echo "utilisateur supprimé";
					session_destroy();
					unset($_SESSION['userI']);	
				}
			}

			function update($login, $email, $password, $firstname, $lastname){
				if(isset($_POST['modifier'])){
					//define 'this' property

					$this->id = $_SESSION['userI']['id'];
		            $this->login = $login;
		            $this->password = $password;
		            $this->email = $email;
		            $this->firstname = $firstname;
		            $this->lastname = $lastname;
					$uqery = mysqli_query($this->bdd, "UPDATE utilisateurs SET login = '$this->login', password = '$this->password', email = '$this->email', firstname = '$this->firstname', lastname = '$this->lastname' WHERE id= '$this->id'");
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
					$iquery = mysqli_query($this->bdd, "SELECT login, password, email, firstname, lastname FROM utilisateurs WHERE id = $this->id");
					$reqs = mysqli_fetch_assoc($iquery);
					
					echo " <b>Voici les informations sur l'utilisateur :</b><br/><br/> <table><tr>";
					foreach ($reqs as $req){
						echo "$req </br>";
					}
					echo "<br/>";
				}
			}

			function getLogin(){
				if(isset($_POST['getlogin'])){
					$this->id = $_SESSION['userI']['id'];
					$lquery = mysqli_query($this->bdd, "SELECT login FROM utilisateurs WHERE id = '$this->id'");
					$req = mysqli_fetch_assoc($lquery);
					foreach ($req as $login){
						echo " le login de l'utilisateur est <b> $login </b>";
					}	
				}
			}

			function getEmail(){
				if(isset($_POST['getemail'])){
					$this->id = $_SESSION['userI']['id'];
					$equery = mysqli_query($this->bdd, "SELECT email FROM utilisateurs WHERE id = '$this->id'");
					$req = mysqli_fetch_assoc($equery);
					foreach ($req as $email){
						echo " l'email de l'utilisateur est <b> $email </b>";	
					}
				}
			}

			function getFirstname(){
				if(isset($_POST['getfirstname'])){
					$this->id = $_SESSION['userI']['id'];
					$fquery = mysqli_query($this->bdd, "SELECT firstname FROM utilisateurs WHERE id = '$this->id'");
					$req = mysqli_fetch_assoc($fquery);
					foreach ($req as $firstname){
						echo " le Firstname de l'utilisateur est <b> $firstname </b>";	
					}
				}
			}

			function getLastname(){
				if(isset($_POST['getlastname'])){
					$this->id = $_SESSION['userI']['id'];
					$laquery = mysqli_query($this->bdd, "SELECT lastname FROM utilisateurs WHERE id = '$this->id'");
					$req = mysqli_fetch_assoc($laquery);
					foreach ($req as $lastname){
						echo " le Lastname de l'utilisateur est <b> $lastname </b>";	
					}
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