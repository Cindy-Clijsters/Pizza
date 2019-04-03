<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__FILE__, 1);

require_once($root . '/vendor/autoload.php');

use App\Business\{CompanyService, TwigService, UserService};
    
// Get the information to display the view
$error = filter_input(INPUT_GET, 'error');

switch($error) {
    case 'invalidOrderStatus' :
        $errorMessage = 'De status van de bestelling is ongeldig.';
        break;
    case 'invalidPizzaStatus' :
        $errorMessage = 'De status van de pizza is ongeldig.';
        break;
    case 'noCompanyInfo':
        $errorMessage = 'De bedrijfsgegevens kunnen niet getoond worden.';
        break;
    default:
        $errorMessage = 'Onbekende fout';
}

// Show the view
$twigSvc = new TwigService();

echo $twigSvc->generateView(
    $root . '/presentation',
    'error.php',
    [
        'errorMessage'      => $errorMessage
    ]
);