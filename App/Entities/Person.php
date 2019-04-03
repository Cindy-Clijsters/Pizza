<?php
declare(strict_types = 1);

namespace App\Entities;

abstract class Person
{
    private $id;
    private $lastname;
    private $firstname;
    private $address;
    private $city;
    private $phone;
    
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
    public function __construct(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone
    ) {
        $this->id         = $id;
        $this->lastname   = $lastname;
        $this->firstname  = $firstname;
        $this->address    = $address;
        $this->city       = $city;
        $this->phone      = $phone;
    }
    
    /**
     * Set the id
     * 
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id):void
    {
        $this->id = $id;
    }
    
    /**
     * Get the id
     * 
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }
    
    /**
     * Set the last name
     * 
     * @param string $lastname
     * 
     * @return void
     */
    public function setLastname(string $lastname):void
    {
        $this->lastname = $lastname;
    }
    
    /**
     * Get the lastname
     * 
     * @return string
     */
    public function getLastname():string
    {
        return $this->lastname;
    }
    
    /**
     * Set the firstname
     * 
     * @param string $firstname
     * 
     * @return void
     */
    public function setFirstname(string $firstname):void
    {
        $this->firstname = $firstname;
    }
    
    /**
     * Get the firstname
     * 
     * @return string
     */
    public function getFirstname():string
    {
        return $this->firstname;
    }
    
    /**
     * Set the address
     * 
     * @param string $address
     * 
     * @return void
     */
    public function setAddress(string $address):void
    {
        $this->address = $address;
    }
    
    /**
     * Get the address
     * 
     * @return string
     */
    public function getAddress():string
    {
        return $this->address;
    }
    
    /**
     * Set the city 
     * 
     * @param City $city
     * 
     * @return void
     */
    public function setCity(City $city):void
    {
        $this->city = $city;
    }
    
    /**
     * Get the city
     * 
     * @return City
     */
    public function getCity():City
    {
        return $this->city;
    }
    
    /**
     * Set the phone number
     * 
     * @param string $phone
     * 
     * @return void
     */
    public function setPhone(string $phone):void
    {
        $this->phone = $phone;
    }
    
    /**
     * Get the phone number
     * 
     * @return string
     */
    public function getPhone():string
    {
        return $this->phone;
    }
    
}