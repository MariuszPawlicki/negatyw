<html class=""><head>
<?php  

require_once("functions.php");
if(isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit();
}else {
$register_errors= $login_error = array();
if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['register'])) {
	$fields = array(
				//'full_name',
				'username',
				'email',
				'password'
	);
	foreach ($fields as $field) {
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	//if ($posted['full_name'] == null)
	//	array_push($register_errors,  sprintf('Podaj imie i nazwisko.', 'neem'));
	if ($posted['email'] == null)
		array_push($register_errors, sprintf('Podaj adres email.', 'neem'));
	if ($posted['password'] == null)
		array_push($register_errors, sprintf('Podaj hasło.', 'neem'));
	if ($posted['username'] == null )
		array_push($register_errors, sprintf('Podaj nazwę użytkownika.', 'neem'));
	if(usernameExist($posted['username'])){
		array_push($register_errors, sprintf('Podana nazwa użytkownika jest już zajęta.', 'neem'));
	}
	if(emailExist($posted['email'])){
		array_push($register_errors, sprintf('Podany email jest już zajęty.', 'neem'));
	}
	$reg_errors = array_filter($register_errors);
	if (empty($reg_errors)) {   //Check whether everything entered to create new user.
		register(/*$posted['full_name'],*/ $posted['username'], $posted['password'], $posted['email']); 
	}	
}
if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['login'])) {	
	$email = stripslashes(trim($_POST['email']));
	$password = stripslashes(trim($_POST['password']));
	$mismatchErr = '';
	if ($password == null )
		array_push($login_error, sprintf('Podaj hasło.', 'neem'));
	if ($email== null )
		array_push($login_error, sprintf('Podaj adres email.', 'neem'));
	$log_error = array_filter($login_error);
	if (empty($log_error)) {   //Check whether everything entered to create new user.
		$loginn = login($email, $password);
		if($loginn){
			$_SESSION['user_id'] = $loginn;
			header('Location: index.php');
			exit();
		}else {
			$mismatchErr .=  sprintf('<p> Podaj poprawne dane. </p>', 'neem');			
		}
	}
}
?>
<head>
<title> Logowanie </title>
</head><body>
    <div class="loginForm"> 
	<?php //print_r($_SESSION); 
	if(isset($_GET['action']) && $_GET['action'] == 'register') { ?>
    <hgroup>
      <h1>Rejestracja</h1>
    </hgroup>
    <form action="" method="post" >
		<?php   if(!empty($reg_errors)) {
					echo '<div class="error">';
					foreach ($register_errors as $error) {
						echo '<p>'.$error.'</p>';
					}
					echo '</div>';
			} ?>
      <!--<div class="group">
        <input type="text" name="full_name" ><span class="highlight"></span><span class="bar"></span>
        <label>Imie i nazwisko</label>
      </div>-->
	  <div class="group">
        <input type="email" name="email" ><span class="highlight"></span><span class="bar"></span>
        <label>Email</label>
      </div>
	  <div class="group">
        <input type="text" name="username" ><span class="highlight"></span><span class="bar"></span>
        <label>Login</label>
      </div>
      <div class="group">
        <input type="text" name="password" ><span class="highlight"></span><span class="bar"></span>
        <label>Hasło</label>
      </div>
	  <input type="hidden" name="register" value="yes" > 
      <button type="submit" class="buttonui "> <span> Dokończ rejestrację </span> </button> 
		
			<a class="buttonui " href="login.php?action=login"> <span> Powrót do logowania  </span> </a>
		
    </form>
	<?php } else { ?>
	<hgroup>
      <h1>Logowanie</h1>
    </hgroup>
    <form action="" method="post" >
	<?php   if(!empty($log_error) || (isset($mismatchErr) && $mismatchErr != '')) {
					echo '<div class="error">';
					foreach ($login_error as $error) {
						echo '<p>'.$error.'</p>';
					}					
					echo $mismatchErr.'</div>';
			} ?>
      <div class="group">
        <input type="text" class="used" name="email" ><span class="highlight"></span><span class="bar"></span>
        <label>Adres email</label>
      </div>
      <div class="group">
        <input type="password" name="password" ><span class="highlight"></span><span class="bar"></span>
        <label>Hasło</label>
      </div>
	  <input type="hidden" name="login" value="yes" >
      <button type="submit" class="buttonui "> <span> Loguj </span> </button>  
		<a class="buttonui " href="login.php?action=register"  <span> Zarejestruj sie </span></a>
    </form>
	<?php } ?>
</body></html>
<?php } ?>