<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        
$root = dirname(__FILE__, 1);

require_once($root . '/vendor/autoload.php');

use App\Business\{OrderService, PizzaService};

use App\Entities\Pizza;

// Check the redirection page
$step = filter_input(INPUT_GET, 'step') ?? 'menu';

$orderSvc     = new OrderService();
$redirectPage = $orderSvc->getRedirectPage($step);

// Check if given values are correct
$action  = filter_input(INPUT_GET, 'action');

if ($action === false || $action === null) {
    header("location:" . $redirectPage);
    exit(0);
}

// Check if pizza exists
if (in_array($action, ['add', 'increase', 'reduce'])) {
    
    $pizzaId = filter_input(INPUT_GET, 'pizza', FILTER_VALIDATE_INT);
    if ($pizzaId === false || $pizzaId === null) {
        header("location:" . $redirectPage);
        exit(0);
    }

    $pizzaSvc = new PizzaService();
    $pizza    = $pizzaSvc->getById($pizzaId);

    if ($pizza === null OR $pizza->getStatus() !== Pizza::STATUS_AVAILABLE) {    
        header("location:" . $redirectPage);
        exit(0);
    }
    
}

// Check if there are order details saved in the session
if (!isset($_SESSION['shoppingCart'])) {
    $shoppingCart             = new stdClass();
    $shoppingCart->orderLines = [];
} else {
    $shoppingCart = unserialize($_SESSION['shoppingCart']);
}

switch ($action) {
    case 'add':
        $amount  = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);

        if ($amount === false || $amount === null) {
            header("location:" . $nextPage);
            exit(0);
        }

        // Add the pizza to the shopping cart
        if (array_key_exists($pizzaId, $shoppingCart->orderLines)) {
            $shoppingCart->orderLines[$pizzaId] += $amount;
        } else {
            $shoppingCart->orderLines[$pizzaId]  = $amount;
        }
        break;
        
    case 'increase':
        // Add the pizza to the shoppping cart
        if (array_key_exists($pizzaId, $shoppingCart->orderLines)) {
            $shoppingCart->orderLines[$pizzaId]++;
        }
        break;
    
    case 'reduce':
        if (array_key_exists($pizzaId, $shoppingCart->orderLines)) {
            if ($shoppingCart->orderLines[$pizzaId] > 1) {
                $shoppingCart->orderLines[$pizzaId]--;
            } else {
                unset($shoppingCart->orderLines[$pizzaId]);
            }
        }
        break;
        
    case 'cancelOrder':
        unset($_SESSION['shoppingCart']);
}

// Save the information to the session
if ($action !== 'cancelOrder') {
    $_SESSION['shoppingCart'] = serialize($shoppingCart);
}

// Redirect to the pizza page
header("location:" . $redirectPage);
exit(0);
