<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__FILE__, 1);

require_once($root . '/vendor/autoload.php');

use App\Business\{CompanyService, TwigService, UserService};

// Get the information to display the view
try {
    $companySvc = new CompanyService();
    $company    = $companySvc->getInfo();
} catch (App\Exceptions\NoCompanyInfoException $ex) {
    header("location: error.php?error=noCompanyInfo");
    exit(0);
}

$user = null;
if (isset($_SESSION['userId'])) {
    $userSvc = new UserService();
    $user    = $userSvc->getById($_SESSION['userId']);
}

// Show the view
$twigSvc = new TwigService();

echo $twigSvc->generateView(
    $root . '/presentation',
    'orderConfirmation.php',
    [
        'company' => $company,
        'user'    => $user
    ]
);