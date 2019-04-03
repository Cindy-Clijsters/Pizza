<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__FILE__, 1);

require_once($root . '/vendor/autoload.php');

use App\Business\{CityService, CompanyService, OrderService, TwigService, UserService};

// Get the redirection page
$redirect = filter_input(INPUT_GET, 'redirect') ?? 'menu';

$orderSvc     = new OrderService();
$redirectPage = $orderSvc->getRedirectPage($redirect);

if (isset($_SESSION['userId'])) {
    header("location:" . $redirectPage);
    exit(0);
}

if (isset($_SESSION['shoppingCart'])) {
    $shoppingCart = unserialize($_SESSION['shoppingCart']);
    if (property_exists($shoppingCart, 'deliveryAddress') && $shoppingCart->deliveryAddress !== null) {
        header("location:order.php");
        exit(0);
    }
}

// Get the information to display the view
try {
    $companySvc = new CompanyService();
    $company    = $companySvc->getInfo();
} catch (App\Exceptions\NoCompanyInfoException $ex) {
    header("location: error.php?error=noCompanyInfo");
    exit(0);
}

$citySvc = new CityService();
$cities  = $citySvc->getAll();

// Get the posted values
$tmpNewUser                 = new stdClass();
$tmpNewUser->lastname       = filter_input(INPUT_POST, 'r-lastname') ?? '';
$tmpNewUser->firstname      = filter_input(INPUT_POST, 'r-firstname') ?? '';
$tmpNewUser->address        = filter_input(INPUT_POST, 'r-address') ?? '';
$tmpNewUser->city           = filter_input(INPUT_POST, 'r-city') ?? '';
$tmpNewUser->phone          = filter_input(INPUT_POST, 'r-phone') ?? '';
$tmpNewUser->createAccount  = filter_input(INPUT_POST, 'r-create-account') ?? '';
$tmpNewUser->mail           = filter_input(INPUT_POST, 'r-mail') ?? '';
$tmpNewUser->password       = filter_input(INPUT_POST, 'r-password') ?? '';
$tmpNewUser->repeatPassword = filter_input(INPUT_POST, 'r-repeat-password') ?? '';

$cookieUserMail = '';
if (isset($_COOKIE['userMail'])) {
    $cookieUserMail = $_COOKIE['userMail'];
}

$tmpUser           = new stdClass();
$tmpUser->mail     = filter_input(INPUT_POST, 'l-mail') ?? $cookieUserMail;
$tmpUser->password = filter_input(INPUT_POST, 'l-password') ?? '';

// Check if the form is posted
$errors = new stdClass();
$errors->isValid = true;

$action = filter_input(INPUT_GET, 'action') ?? '';
    
if ($_POST) {
    
    switch ($action) {
        case 'register':
            
            // Validate the user information
            $userSvc = new UserService();
            $errors  = $userSvc->validateUser($tmpNewUser);
            
            if ($errors->isValid === true) {
                
                if ($tmpNewUser->createAccount === '1') {
                    
                    // Hash the password 
                    $passwordHash = password_hash($tmpNewUser->password, PASSWORD_DEFAULT);

                    // Insert the user id
                    $userId = $userSvc->insert(
                        $tmpNewUser->lastname,
                        $tmpNewUser->firstname,
                        $tmpNewUser->address,
                        intVal($tmpNewUser->city),
                        $tmpNewUser->phone,
                        $tmpNewUser->mail,
                        $passwordHash
                    );

                    $_SESSION['userId'] = $userId;
                    
                    setcookie('userMail', $tmpUser->mail, time() + (3600 * 24 * 365));

                } else {
                    
                    $deliveryAddress            = new stdClass();
                    $deliveryAddress->lastname  = $tmpNewUser->lastname;
                    $deliveryAddress->firstname = $tmpNewUser->firstname;
                    $deliveryAddress->address   = $tmpNewUser->address;
                    $deliveryAddress->cityId    = intVal($tmpNewUser->city);
                    $deliveryAddress->phone     = $tmpNewUser->phone;

                    $shoppingCart = unserialize($_SESSION['shoppingCart']);
                    $shoppingCart->deliveryAddress = $deliveryAddress;
                    $_SESSION['shoppingCart'] = serialize($shoppingCart);
                    
                }
                
                header("location:" . $redirectPage);
                exit(0);
            }

            break;
        case 'login':
            
            // Validate the user information
            $userSvc = new UserService();
            $errors  = $userSvc->validateLoginForm($tmpUser);
            
            if ($errors->isValid === true) {
                
                // Get the informatio nof the user
                $user = $userSvc->getByMail($tmpUser->mail);
                
                if (
                   $user !== null 
                   && password_verify($tmpUser->password, $user->getPassword()) 
                ) {
                    
                    $_SESSION['userId'] = $user->getId();
                    
                    setcookie('userMail', $tmpUser->mail, time() + (3600 * 24 * 365));

                    // Redirecten naar juiste pagina
                    header("location:" . $redirectPage);
                    exit(0);
                    
                } else {
                    
                    $errors->password = "Foutief e-mailadres of wachtwoord.";
                    $errors->isValid  = false;
                    
                }
            }
            break;
    }
}

// Show the view
$twigSvc = new TwigService();

echo $twigSvc->generateView(
    $root . '/presentation',
    'loginOrRegister.php',
    [
        'company'    => $company,
        'redirect'   => $redirect,
        'cities'     => $cities,
        'tmpUser'    => $tmpUser,
        'tmpNewUser' => $tmpNewUser,
        'errors'     => $errors,
        'action'     => $action
    ]
);