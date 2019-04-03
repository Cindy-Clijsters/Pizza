<?php
declare (strict_types = 1);

namespace App\Entities;

class DeliveryAddress extends Person implements DeliveryAddressInterface
{
    private static $idMap = [];
    
    /**
     * Constructor function 
     * 
     * @param int $id
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param City $city
     * @param string $phone
     * 
     * @return void
     */
    private function __construct(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone
    ) {
        parent::__construct(
            $id,
            $lastname,
            $firstname,
            $address,
            $city,
            $phone
        );
    }
    
    /**
     * Create a new delivery address object
     * 
     * @param int $id
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param City $city
     * @param string $phone
     * 
     * @return DeliveryAddress
     */
    public static function create(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone
    ):DeliveryAddress {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new DeliveryAddress(
                $id,
                $lastname,
                $firstname,
                $address,
                $city,
                $phone
            );
        }
        
        return self::$idMap[$id];
    }    
}