<?php 
define("APPLICATION_PATH", dirname(__FILE__)."/application/");
define("SITE_ROOT", "http://localhost/annonce_mcd/");


// PDO Connect
require APPLICATION_PATH.'configs/connect.php';

// Authentification 
require APPLICATION_PATH.'configs/auth.php';

// Dispatcher
require_once APPLICATION_PATH.'dispatcher.php';

// Erreurs status 
require_once APPLICATION_PATH.'configs/rest.php';

$controller = isset($_GET['controller'])?$_GET['controller']:'accueil';
$action = isset($_GET['action'])?$_GET['action']:'index';

include_once APPLICATION_PATH.'controllers/'.$controller.'/'.$action.'.php';
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_ROOT; ?>public/js/select.js"></script>
	</head>
	<body>
		<?php include_once APPLICATION_PATH.'views/'.$controller.'/'.$action.'.phtml'; ?>
	</body>
</html>