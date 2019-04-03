<?php
declare(strict_types = 1);

namespace App\Entities;

interface DeliveryAddressInterface
{
    public static function create(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone     
    ):DeliveryAddress;
    
    public function setId(int $id):void;
    public function getId():int;
    public function setLastname(string $lastname):void;
    public function getLastname():string;
    public function setFirstname(string $firstname):void;
    public function getFirstname():string;
    public function setAddress(string $address):void;
    public function getAddress():string;
    public function setCity(City $city):void;
    public function getCity():City;
    public function setPhone(string $phone):void;
    public function getPhone():string;
}