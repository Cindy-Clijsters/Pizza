<?php
declare(strict_types = 1);

namespace App\Entities;

class User extends Person implements UserInterface
{
    private $password;
    private $remarks;
    private $promotions;
    
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
     * @param string $password
     * @param string $remarks
     * @param bool $promotions
     * 
     * @return void
     */
    private function __construct(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone,
        string $password,
        string $remarks,
        bool $promotions
    ) {
        parent::__construct(
            $id,
            $lastname,
            $firstname,
            $address,
            $city,
            $phone
        );
        
        $this->password   = $password;
        $this->remarks    = $remarks;
        $this->promotions = $promotions;
    }
    
    /**
     * Create a new user object
     * 
     * @param int $id
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param City $city
     * @param string $phone
     * @param string $password
     * @param string $remarks
     * @param bool $promotions
     * 
     * @return User
     */
    public static function create(
        int $id,
        string $lastname,
        string $firstname,
        string $address,
        City $city,
        string $phone,
        string $password,
        string $remarks,
        bool $promotions
    ):User {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new User(
                $id,
                $lastname,
                $firstname,
                $address,
                $city,
                $phone,
                $password,
                $remarks,
                $promotions
            );
        }
        
        return self::$idMap[$id];
    }
                
    /**
     * Set the mail address
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
     * Get the mail address
     * 
     * @return string
     */
    public function getMail():string
    {
        return $this->mail;
    }
    
    /**
     * Set the password
     * 
     * @param string $password
     * 
     * @return void
     */
    public function setPassword(string $password):void
    {
        $this->password = $password;
    }
    
    /**
     * Get  the password
     * 
     * @return string
     */
    public function getPassword():string
    {
        return $this->password;
    }
    
    /**
     * Set the remarks
     * 
     * @param string $remarks
     * 
     * @return void
     */
    public function setRemarks(string $remarks):void
    {
        $this->remarks = $remarks;
    }
    
    /**
     * Get the remarks
     * 
     * @return string
     */
    public function getRemarks():string
    {
        return $this->remarks;
    }
    
    /**
     * Set the promotions option
     * 
     * @param bool $promotions
     * 
     * @return void
     */
    public function setPromotions(bool $promotions):void
    {
        $this->promotions = $promotions;
    }
    
    /**
     * Get the promotions option
     * 
     * @return bool
     */
    public function getPromotions():bool 
    {
        return $this->promotions;
    }
}