<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__FILE__, 1);

if (!isset($_SESSION['shoppingCart'])) {
    header("location:menu.php");
    exit(0);
} else {
    $shoppingCart = unserialize($_SESSION['shoppingCart']);
    if (empty($shoppingCart) || !property_exists($shoppingCart, 'orderLines') || empty($shoppingCart->orderLines)) {
        header("location:menu.php");
        exit(0);
    }
}

require_once($root . '/vendor/autoload.php');

use App\Business\{CityService, CompanyService, DeliveryAddressService, OrderService, OrderLineService, TwigService, UserService, ValidationService};
use App\Exceptions\InvalidStatusException;

// Get the shopping cart info
$user = null;
if (isset($_SESSION['userId'])) {
    $userSvc = new UserService();
    $user    = $userSvc->getById($_SESSION['userId']);
}

$orderSvc         = new OrderService();
$shoppingCart     = unserialize($_SESSION['shoppingCart']);
$shoppingCartInfo = $orderSvc->getShoppingCartInfo($shoppingCart, 'order', $user);

// Get the information to display the view
try {
    $companySvc = new CompanyService();
    $company    = $companySvc->getInfo();
} catch (App\Exceptions\NoCompanyInfoException $ex) {
    header("location: error.php?error=noCompanyInfo");
    exit(0);
}

$now = new DateTime();
$now->add(new DateInterval('PT45M'));

$deliveryDate = filter_input(INPUT_POST, 'delivery-date') ?? $now->format('Y-m-d');
$deliveryTime = filter_input(INPUT_POST, 'delivery-time') ?? $now->format('H:i');

$citySvc = new CityService();
$cities  = $citySvc->getAll();

$deliveryAddress = new stdClass();
$deliveryAddress->lastname  = filter_input(INPUT_POST, 'lastname') ?? $shoppingCartInfo->deliveryAddress->lastname;
$deliveryAddress->firstname = filter_input(INPUT_POST, 'firstname') ?? $shoppingCartInfo->deliveryAddress->firstname;
$deliveryAddress->address   = filter_input(INPUT_POST, 'address') ?? $shoppingCartInfo->deliveryAddress->address;
$deliveryAddress->city      = filter_input(INPUT_POST, 'city') ?? $shoppingCartInfo->deliveryAddress->cityId;
$deliveryAddress->phone     = filter_input(INPUT_POST, 'phone') ?? $shoppingCartInfo->deliveryAddress->phone;

$remarks = filter_input(INPUT_POST, 'remarks') ?? '';

// Check if the form is posted
$errors = new stdClass();
$errors->isValid = true;

if ($_POST) {
    
    $validationSvc = new ValidationService();
    
    $userSvc = new UserService();
    $errors  = $userSvc->validateAddress($deliveryAddress);
    
    $homeDelivery = $citySvc->checkHomeDelivery($deliveryAddress->city);
    
    if ($homeDelivery === false) {
        $errors->city    = 'Thuislevering is niet mogelijk in deze gemeente!';
        $errors->isValid = false;
    }
    
    $deliveryDateErrors = $validationSvc->validateTextField($deliveryDate, 10);
    
    if ($deliveryDateErrors === '') {
        $deliveryDateErrors = $validationSvc->checkValidDateTime($deliveryDate, 'Y-m-d');
    }
    
    if ($deliveryDateErrors !== '') {
        $errors->deliveryDate = $deliveryDateErrors;
        $errors->isValid      = false;
    }
    
    $deliveryTimeErrors = $validationSvc->validateTextField($deliveryTime, 5);
    
    if ($deliveryTimeErrors === '') {
        $deliveryTimeErrors = $validationSvc->checkValidDateTime($deliveryTime, 'H:i');
    }
    
    if ($deliveryTimeErrors !== '') {
        $errors->deliveryTime = $deliveryTimeErrors;
        $errors->isValid      = false;
    }
    
    if ($deliveryDateErrors === '' && $deliveryTimeErrors === '') {
        $minDeliveryDateTime = new DateTime();
        $minDeliveryDateTime->add(new DateInterval('PT30M'));

        $wantedDeliveryDateTime = $deliveryDate . ' ' . $deliveryTime . ':00';
        
        $validDateTime = $validationSvc->checkDateTimeBiggerThen(
            $wantedDeliveryDateTime,
            $minDeliveryDateTime->format('Y-m-d H:i:s')
        );
        
        if ($validDateTime === false) {
            $errors->deliveryTime = 'Het tijdstip van levering moet min. 30 minuten in de toekomst liggen';
            $errors->isValid      = false;
        }
    }
    
    $remarkErrors = $validationSvc->checkMaxLength($remarks, 2000);
    
    if ($remarkErrors !== '') {
        $errors->remarks = $remarkErrors;
        $errors->isValid = false;
    }
    
    if ($errors->isValid === true) {
        
        // Save the delivery address
        $deliveryAddressSvc  = new DeliveryAddressService();
        $saveDeliveryAddress = $deliveryAddressSvc->checkNeedToBeSaved($deliveryAddress, $user);
        
        $deliveryAddressId = null;
        
        if ($saveDeliveryAddress === true) {
            $deliveryAddressId = $deliveryAddressSvc->insert(
                $deliveryAddress->lastname,
                $deliveryAddress->firstname,
                $deliveryAddress->address,
                intval($deliveryAddress->city),
                $deliveryAddress->phone
            );
        }
        
        // Save the order
        $userId = null;
        if ($user !== null) {
            $userId = $user->getId();
        }
        
        $deliveryDateTime = new DateTime($deliveryDate . ' ' . $deliveryTime . ':00');
        
        try {
            $orderId = $orderSvc->insert(
                $userId,
                $deliveryAddressId,
                $deliveryDateTime,
                $remarks
            );
        } catch (InvalidStatusException $ex) {
            header("location: error.php?error=invalidOrderStatus");
            exit(0);
        }

        
        // Save the order lines
        $orderLineSvc = new OrderLineService();
        foreach($shoppingCartInfo->orderLines as $orderLine) {
            $orderLineSvc->insert(
                $orderId,
                $orderLine->pizza->getId(),
                $orderLine->amount,
                $orderLine->unitPrice
            );
        }
        
        // Show confirmation page
        unset($_SESSION['shoppingCart']);
        
        header("location:orderConfirmation.php");
        exit(0);   
    }
    
} else {
    
    $homeDelivery = $citySvc->checkHomeDelivery($deliveryAddress->city);
    
    if ($homeDelivery === false) {
        $errors->city    = 'Thuislevering is niet mogelijk in deze gemeente!';
        $errors->isValid = false;
    }
    
}

// Show the view
$twigSvc = new TwigService();

echo $twigSvc->generateView(
    $root . '/presentation',
    'order.php',
    [
        'company'          => $company,
        'shoppingCartInfo' => $shoppingCartInfo,
        'deliveryDate'     => $deliveryDate,
        'deliveryTime'     => $deliveryTime,
        'cities'           => $cities,
        'deliveryAddress'  => $deliveryAddress,
        'remarks'          => $remarks,
        'user'             => $user,
        'errors'           => $errors
    ]
);