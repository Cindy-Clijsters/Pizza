<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\DeliveryAddressDAO;
use App\Entities\User;

use stdClass;

class DeliveryAddressService
{
    /**
     * Check if the delivery address needs to be saved
     * 
     * @param stdClass $deliveryAddress
     * @param User|null $user
     * 
     * @return bool
     */
    public function checkNeedToBeSaved(stdClass $deliveryAddress, ?User $user):bool
    {
        $result = true;
        
        if (
            $user !== null
            && (trim($deliveryAddress->lastname) === trim($user->getLastname()))
            && (trim($deliveryAddress->firstname) === trim($user->getFirstname()))
            && (trim($deliveryAddress->address) === trim($user->getAddress()))
            && (intval($deliveryAddress->city) === intval($user->getCity()->getId()))    
            && (trim($deliveryAddress->phone) === trim($user->getPhone()))
        ) {
            $result = false;
        }
        
        return $result;
    }
    
    /**
     * Insert a delivery address
     * 
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param int $cityId
     * @param string $phone
     * 
     * @return int
     */
    public function insert(
        string $lastname,
        string $firstname,
        string $address,
        int $cityId,
        string $phone
    ):int {
        $deliveryAddressDAO = new DeliveryAddressDAO();
        $deliveryAddressId = $deliveryAddressDAO->insert(
            $lastname,
            $firstname,
            $address,
            $cityId,
            $phone
        );
        
        return $deliveryAddressId;
    }
}