<?php 
define("APPLICATION_PATH", dirname(__FILE__)."/application/");
define("SITE_ROOT", dirname(__FILE__));

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
<?php include_once APPLICATION_PATH.'views/'.$controller.'/'.$action.'.phtml'; ?>
