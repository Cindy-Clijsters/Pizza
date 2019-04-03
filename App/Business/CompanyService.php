<?php
declare(strict_types = 1);

namespace App\Business;

use App\Data\CompanyDAO;
use App\Entities\Company;

class CompanyService 
{
    /**
     * Get the information of the company
     * 
     * @return Company|null
     */
    public function getInfo():?Company 
    {
        $companyDAO = new CompanyDAO();
        $company    = $companyDAO->getInfo();
        
        return $company;
    }
}