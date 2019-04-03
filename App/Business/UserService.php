<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\UserDAO;
use App\Entities\User;

use stdClass;

class UserService
{
    /**
     * Get a user by it's mail address
     * 
     * @param string $mail
     * 
     * @return User|null
     */
    public function getByMail(string $mail):?User
    {
        $userDAO = new UserDAO();
        $user    = $userDAO->getByMail($mail);
        
        return $user;
    }
    
    /**
     * Get a user by it's id
     * 
     * @param int $id
     * 
     * @return User|null
     */
    public function getById(int $id):?User
    {
        $userDAO = new UserDAO();
        $user    = $userDAO->getById($id);
        
        return $user;
    }
    
    /**
     * Insert a new user
     * 
     * @param string $lastname
     * @param string $firstname
     * @param string $address
     * @param int $cityId
     * @param string $phone
     * @param string $mail
     * @param string $passwordHash
     * @param string $remark
     * @param bool $promotions
     * 
     * @return void
     */
    public function insert(
        string $lastname,
        string $firstname,
        string $address,
        int $cityId,
        string $phone,
        string $mail,
        string $passwordHash,
        string $remark = '',
        bool $promotions = false
    ) {
        $userDAO = new UserDAO();
        $userId  = $userDAO->insert(
            $lastname,
            $firstname,
            $address,
            $cityId,
            $phone,
            $mail,
            $passwordHash,
            $remark,
            $promotions
        );
        
        return $userId;
    }
    
    /**
     * Validate the address information
     * 
     * @param stdClass $address
     * 
     * @return stdClass
     */
    public function validateAddress(stdClass $address):stdClass
    {
        $validationSvc = new ValidationService();
        
        $errors          = new stdClass();
        $errors->isValid = true;
        
        $lastnameErrors = $validationSvc->validateTextField($address->lastname, 50);
        
        if ($lastnameErrors !== '') {
            $errors->lastname = $lastnameErrors;
            $errors->isValid  = false;
        }
        
        $firstnameErrors = $validationSvc->validateTextField($address->firstname, 50);
        
        if ($firstnameErrors !== '') {
            $errors->firstname = $firstnameErrors;
            $errors->isValid   = false;
        }
        
        $addressErrors = $validationSvc->validateTextField($address->address, 100);
        
        if ($addressErrors !== '') {
            $errors->address = $addressErrors;
            $errors->isValid = false;
        }
        
        $cityErrors = $validationSvc->validateTextField($address->city, 10);
        
        if ($cityErrors === '') {
            $cityErrors = $validationSvc->checkCityIdExists(intVal($address->city));
        }
        
        if ($cityErrors !== '') {
            $errors->city    = $cityErrors;
            $errors->isValid = false;
        }
        
        $phoneErrors = $validationSvc->validateTextField($address->phone, 25);
        
        if ($phoneErrors === '') {
            $phoneErrors = $validationSvc->checkValidTelephoneNumber($address->phone);
        }
        
        if ($phoneErrors !== '') {
            $errors->phone   = $phoneErrors;
            $errors->isValid = false;
        }
        
        return $errors;
    }
                    
    /**
     * Validate a user
     * 
     * @param stdClass $tmpUser
     * 
     * @return stdClass
     */
    public function validateUser(stdClass $tmpUser):stdClass
    {
        $validationSvc = new ValidationService();
        
        $errors = $this->validateAddress($tmpUser);
        
        if ($tmpUser->createAccount === '1') {
        
            $mailErrors = $validationSvc->validateTextField($tmpUser->mail, 100);

            if ($mailErrors === '') {
                $mailErrors = $validationSvc->checkEmail($tmpUser->mail);
            }

            if ($mailErrors === '') {
                $mailErrors = $validationSvc->checkUniqueUserMail($tmpUser->mail);
            }

            if ($mailErrors !== '') {
                $errors->mail    = $mailErrors;
                $errors->isValid = false;
            }

            $passwordErrors = $validationSvc->validateTextField($tmpUser->password, 50, 8);

            if ($passwordErrors !== '') {
                $passwordErrors = $validationSvc->checkSafePassword($tmpUser->password);
            }

            if ($passwordErrors !== '') {
                $errors->password = $passwordErrors;
                $errors->isValid = false;
            }

            $repeatPasswordErrors = $validationSvc->validateTextField($tmpUser->repeatPassword, 50, 8);

            if ($passwordErrors === '' && $repeatPasswordErrors === '') {
                $repeatPasswordErrors = $validationSvc->checkRepeatPassword($tmpUser->password, $tmpUser->repeatPassword);
            }

            if ($repeatPasswordErrors !== '') {
                $errors->repeatPassword = $repeatPasswordErrors;
                $errors->isValid        = false;
            }
            
        }
        
        return $errors;
    }
    
    /**
     * Validate a user login form
     * 
     * @param stdClass $tmpUser
     * 
     * @return stdClass
     */
    public function validateLoginForm(stdClass $tmpUser)
    {
        $validationSvc = new ValidationService();
        
        $errors          = new stdClass();
        $errors->isValid = true;
        
        $mailErrors = $validationSvc->validateTextField($tmpUser->mail, 100);
        
        if ($mailErrors === '') {
            $mailErrors = $validationSvc->checkEmail($tmpUser->mail);
        }
        
        if ($mailErrors !== '') {
            $errors->mail    = $mailErrors;
            $errors->isValid = false;
        }
        
        $passwordErrors = $validationSvc->validateTextField($tmpUser->password, 50);
        
        if ($passwordErrors !== '') {
            $errors->password = $passwordErrors;
            $errors->isValid  = false;
        }
        
        return $errors;
    }
}