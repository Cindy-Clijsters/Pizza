<?php
declare(strict_types = 1);

namespace App\Entities;

/**
 * Class Company
 * 
 * Hold the properties of the company
 */
class Company
{
    private $id;
    private $name;
    private $address;
    private $city;
    private $phone;
    private $mail;
    private $vatNumber;
    private $aboutUs;
    
    private static $idMap = [];    
    
    /**
     * Constructor function 
     * 
     * @param int $id
     * @param string $name
     * @param string $address
     * @param City $city
     * @param string $phone
     * @param string $mail
     * @param string $vatNumber
     * @param string $aboutUs
     * 
     * @return void
     */
    private function __construct(
        int $id,
        string $name,
        string $address,
        City $city,
        string $phone,
        string $mail,
        string $vatNumber,
        string $aboutUs
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->address   = $address;
        $this->mail      = $mail;
        $this->vatNumber = $vatNumber;
        $this->aboutUs   = $aboutUs;
    }
    
    /**
     * Create an company object
     * 
     * @param int $id
     * @param string $name
     * @param string $address
     * @param \App\Entities\City $city
     * @param string $phone
     * @param string $mail
     * @param string $vatNumber
     * @param string $aboutUs
     * 
     * @return Company
     */
    public static function create(
        int $id,
        string $name,
        string $address,
        City $city,
        string $phone,
        string $mail,
        string $vatNumber,
        string $aboutUs            
    ) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Company(
                $id,
                $name,
                $address,
                $city,
                $phone,
                $mail,
                $vatNumber,
                $aboutUs     
            );
        }
        
        return self::$idMap[$id];              
    }
    
    /**
     * Set the id of the company
     * 
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }
    
    /**
     * Return the id of the company
     * 
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }
    
    /**
     * Set the name of the company
     * 
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name):void
    {
        $this->name = $name;
    }
    
    /**
     * Get the name of the company
     * 
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }
    
    /**
     * Set the address of the company
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
     * Get the address of the company
     * 
     * @return string
     */
    public function getAddress():?string
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
     * Set the phone of the company
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
     * Get the phone of the company
     * 
     * @return string
     */
    public function getPhone():string
    {
        return $this->phone;
    }
    
    /**
     * Set the mail of the company
     * 
     * @param string $mail
     * 
     * @return void
     */
    public function setMail(string $mail):void
    {
        $this->mail = $mail;
    }
    
    /**
     * Get the mail of the company
     * 
     * @return string
     */
    public function getMail():string
    {       
        return $this->mail;
    }
    
    /**
     * Set the vat number of the company
     * 
     * @param string $vatNumber
     * 
     * @return void
     */
    public function setVatNumber(string $vatNumber):void
    {
        $this->vatNumber = $vatNumber;
    }
    
    /**
     * Get the vat number of the company
     * 
     * @return string
     */
    public function getVatNumber():string
    { 
        return $this->vatNumber;
    }
    
    /**
     * Set the about us information of the company
     * 
     * @param string $aboutUs
     * 
     * @return void
     */
    public function setAboutUs(string $aboutUs):void
    {
        $this->aboutUs = $aboutUs;
    }
    
    /**
     * Get the about us information of the company
     * 
     * @return string
     */
    public function getAboutUs():string
    {
        return $this->aboutUs;
    }
}