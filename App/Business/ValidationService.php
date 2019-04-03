<?php
declare(strict_types = 1);

namespace App\Business;

use DateTime;

class ValidationService
{
    /**
     * Combined function to validate a text field
     * 
     * @param string $value
     * @param int $maxLength
     * @param int|null $minLength
     * 
     * @return string
     */
    public function validateTextField(
        string $value,
        int $maxLength,
        ?int $minLength = null
    ) {
        $result = $this->checkRequired($value);
        
        if ($result === '') {
            $result = $this->checkMaxLength($value, $maxLength);
        }
        
        if ($result === '' && $minLength !== null) {
            $result = $this->checkMinLength($value, $minLength);
        }
        
        return $result;
    }
    
    /**
     * Check if the value is a valid e-mail address
     * 
     * @param string $value
     * 
     * @return string
     */
    public function checkEmail(string $value):string
    {
        $result = '';
        
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $result = 'Dit veld moet een geldig e-mail adres bevatten.';
        }
        
        return $result;
    }      
    
    /**
     * Check if the phone number is valid (only BE numbers)
     * 
     * @param string $value
     * 
     * @return string
     */
    public function checkValidTelephoneNumber(string $value):string
    {
        $result = '';
        
        if (
            !preg_match('/^\d{3,4}\/?\d{2}\s?\d{2}\s?\d{2}$/', $value)
            && !preg_match('/^\d{2}\/?\d{3}\s?\d{2}\s?\d{2}$/', $value)
        ) {
            $result = 'Dit veld heeft een foutief formaat.';;
        }
        
        return $result;
    }      
    
    /**
     * Check if the password is safe (must contain at least one letter, one 
     * capital letter, one digit and one special character)
     * 
     * @param string $value
     * 
     * @return string
     */
    public function checkSafePassword(string $value):string
    {
        $result = '';
        
        if (!preg_match('/^((?=.*\d)(?=.*[A-Z])(?=.*[a-z])((?=.*\W)|(?=.*\_)).{8,50})/', $value)) {
            $result = 'Het wachtwoord moet minstens 1 letter, 1 hoofdletter, 1 cijfer en een speciaal karakter bevatten';
        }
        
        return $result;
    }      
    
    /**
     * Check if the passwords agree
     * 
     * @param string $password
     * @param string $confirmPassword
     * 
     * @return string
     */
    public function checkRepeatPassword(
        string $password,
        string $confirmPassword
    ):string {
        $result = '';
        
        if ($password !== $confirmPassword) {
            $result = 'De wachtwoorden komen niet overeen.';
        }
        
        return $result;
    }        
    
    /**
     * Check if the mail of the user is unique
     * 
     * @param string $mail
     * 
     * @return string
     */
    public function checkUniqueUserMail(string $mail)
    {
        $result = '';
        
        $userSvc = new UserService();
        $user    = $userSvc->getByMail($mail);
        
        if ($user !== null) {
            $result = 'Dit veld moet een uniek e-mail adres bevatten.';
        }
        
        return $result;
    }
    
    /**
     * Check if the id of the city exitst
     * 
     * @param int $id
     * 
     * @return string
     */
    public function checkCityIdExists(int $id)
    {
        $result = '';
        
        $citySvc = new CityService();
        $city    = $citySvc->getById($id);
        
        if ($city === null) {
            $result = 'Dit veld moet een bestaande stad bevatten.';
        }
        
        return $result;
    }
    
    /**
     * Check if the given value is not empty
     * 
     * @param string $value
     * 
     * return string
     */
    private function checkRequired(string $value):string
    {
        $result = '';
        
        if (trim($value) === '') {
            $result = 'Dit is een verplicht veld.';
        }
        
        return $result;
    }    
    
    /**
     * Check the min length of the value
     * 
     * @param string $value
     * @param int $length
     * 
     * @return string
     */
    private function checkMinLength(string $value, int $length):string
    {
        $result = '';
        
        if (strlen($value) < $length) {
            $result = 'Dit veld moet min. ' . $length . ' karakters bevatten.';
        }
        
        return $result;
    }
    
    /**
     * Check the max length of the value
     * 
     * @param string $value
     * @param int $length
     * 
     * @return string
     */
    public function checkMaxLength(string $value, int $length):string
    {
        $result = '';
        
        if (strlen($value) > $length) {
            $result = 'Dit veld mag max. ' . $length . ' karakters bevatten.';
        }
        
        return $result;
    }    
    
    /**
     * Show a valid date time function
     * 
     * @param string $value
     * @param string $format
     * 
     * @return string
     */
    public function checkValidDateTime(string $value, string $format = 'Y-m-d H:i:s'):string
    {
        $result = '';
        
        $dateTime = DateTime::createFromFormat($format, $value);
        
        if (!$dateTime || $dateTime->format($format) !== $value) {
            $result = 'Dit veld moet een geldige waarde bevatten';
        }
        
        return $result;
    }
    
    
    /**
     * Check if the first date time is bigger than the last date time
     * 
     * @param string $firstDateTime (date time notation)
     * @param string $lastDateTime (date time notation)
     * @param string $format
     * 
     * @return bool
     */
    public function checkDateTimeBiggerThen(
        $firstDateTime,
        $lastDateTime,
        $format = 'Y-m-d H:i:s'
    ):bool {
        // Set the result
        $result = false;
        
        // Format the dates to a date time string
        $fd = DateTime::createFromFormat($format, $firstDateTime);
        $ld = DateTime::createFromFormat($format, $lastDateTime);
        
        if ($fd > $ld) {
            $result = true;
        }
        
        return $result;
    }
    

}