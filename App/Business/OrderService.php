<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\OrderDAO;
use App\Entities\Order;
use App\Entities\User;

use DateTime;
use stdClass;

class OrderService
{
    /**
     * Initialize the shopping cart information class
     * 
     * @return stdClass
     */
    public function initializeShoppingCartInfo()
    {
        $shoppingCartInfo = new stdClass();
        $shoppingCartInfo->user = null;
        $shoppingCartInfo->deliveryAddress = null;
        $shoppingCartInfo->deliveryDate = null;
        $shoppingCartInfo->orderLines = [];
        $shoppingCartInfo->totalPrice = 0; 
        $shoppingCartInfo->usePromoPrice = false;
        
        return $shoppingCartInfo;
    }
    
    /**
     * Get the information to display the shopping cart
     * 
     * @param stdClass $shoppingCart (array('orderLines' => []))
     * @param string $step
     * @param User|null $user
     * 
     * @return stdClass
     */
    public function getShoppingCartInfo(stdClass $shoppingCart, string $step, ?User $user = null):stdClass
    {
        // Initialize the results
        $shoppingCartInfo = $this->initializeShoppingCartInfo();             
        
        // Get the information of the pizza's
        if (property_exists($shoppingCart, 'orderLines') &&  !empty($shoppingCart->orderLines)) {
            
            $pizzaIds = array_keys($shoppingCart->orderLines);

            if (!empty($pizzaIds)) {

                $pizzaSvc = new PizzaService();
                $pizzas   = $pizzaSvc->getMultipleById($pizzaIds);

                foreach($pizzas as $pizza) {
                    $orderLine  = new stdClass();
                    $amount     = $shoppingCart->orderLines[$pizza->getId()];

                    if ($user !== null && $user->getPromotions() === true) {
                        $unitPrice = $pizza->getAdjustedPromotionPrice();
                    } else {
                        $unitPrice = $pizza->getPrice();
                    }

                    $totalPrice = $unitPrice * $amount;

                    $orderLine->pizza       = $pizza;
                    $orderLine->amount      = $amount;
                    $orderLine->unitPrice   = $unitPrice;
                    $orderLine->totalPrice  = $totalPrice;

                    array_push($shoppingCartInfo->orderLines, $orderLine);

                    $shoppingCartInfo->totalPrice += $totalPrice;
                }

            }

        }
        
        if ($step === 'order') {

            // Get the delivery address information
            $deliveryAddress = $this->getDeliveryAddress($shoppingCart, $user);
            $shoppingCartInfo->deliveryAddress = $deliveryAddress;
        
        }

        return $shoppingCartInfo;
    }
    
    /**
     * Get the delivery address
     * 
     * @param stdClass $shoppingCart
     * @param User|null $user
     * 
     * @return stdClass
     */
    private function getDeliveryAddress(stdClass $shoppingCart, ?User $user):stdClass
    {
        // Get the delivery address information
        if (property_exists($shoppingCart, 'deliveryAddress')) {

            $deliveryAddress = $shoppingCart->deliveryAddress;

        } else {

            $deliveryAddress            = new stdClass();
            $deliveryAddress->lastname  = $user->getLastname();
            $deliveryAddress->firstname = $user->getFirstname();
            $deliveryAddress->address   = $user->getAddress();
            $deliveryAddress->cityId    = $user->getCity()->getId();
            $deliveryAddress->phone     = $user->getPhone();

        }
        
        return $deliveryAddress;
    }
    
    /**
     * Get the redirect page
     * 
     * @param string $step
     * 
     * @return string
     */
    public function getRedirectPage(string $step)
    {
        switch ($step) {
            case 'order':
                $redirectPage = 'order.php';
                break;
            default:
                $redirectPage = 'menu.php';
        }
        
        return $redirectPage;
    }
    
    /**
     * Insert a new order
     * 
     * @param int|null $userId
     * @param int|null $deliveryAddressId
     * @param DateTime $deliveryDateTime
     * @param string $remarks
     * @param string $status
     * 
     * @return int
     */    
    public function insert(
        ?int $userId,
        ?int $deliveryAddressId,
        DateTime $deliveryDateTime,
        string $remarks,
        string $status = Order::STATUS_PLACED
    ) {
        $orderDAO = new OrderDAO();
        $orderId  = $orderDAO->insert(
            $userId,
            $deliveryAddressId,
            $deliveryDateTime,
            $remarks,
            $status
        );
        
        return $orderId;
    }
    
    
}