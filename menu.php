<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__FILE__, 1);

require_once($root . '/vendor/autoload.php');

use App\Business\{CompanyService, OrderService, PizzaService, TwigService, UserService};
    
// Get the company information
try {
    $companySvc = new CompanyService();
    $company    = $companySvc->getInfo();
} catch (App\Exceptions\NoCompanyInfoException $ex) {
    header("location: error.php?error=noCompanyInfo");
    exit(0);
}

// Get the pizza information
$pizzaSvc = new PizzaService();
$pizzas   = $pizzaSvc->getAvailablePizzas();

// Get the user information
$user = null;

if (isset($_SESSION['userId'])) {
    $userSvc = new UserService();
    $user    = $userSvc->getById($_SESSION['userId']);
}

// Get the order information
$orderSvc = new OrderService();
if (!isset($_SESSION['shoppingCart'])) {
    $shoppingCartInfo = $orderSvc->initializeShoppingCartInfo();
} else {
    $shoppingCart     = unserialize($_SESSION['shoppingCart']);
    $shoppingCartInfo = $orderSvc->getShoppingCartInfo($shoppingCart, 'pizza', $user);
}

// Show the view
$twigSvc = new TwigService();

echo $twigSvc->generateView(
    $root . '/presentation',
    'menu.php',
    [
        'company'           => $company,
        'user'              => $user,
        'pizzas'            => $pizzas,
        'amountRange'       => range(1, 10),
        'shoppingCartInfo'  => $shoppingCartInfo
    ]
);