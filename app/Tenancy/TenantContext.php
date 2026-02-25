<?php
namespace App\Tenancy;

use DateException;
use DomainException;
 
class TenantContext{
    private ?int $companyId = null;
    private ?string $companyUuid = null;

    public function set(int $companyId , string $companyUuid){
        $this->companyId = $companyId;
        $this->companyUuid = $companyUuid;
    }
    public function getCompanyId(): ?int{
        if(!$this->companyId){
            throw new DomainException('Tenant not resolved.');
        }
        return $this->companyId;
    }
    public function getCompanyUuid(){
        if(!$this->companyUuid){
            throw new DomainException('Tenant not resolved.');
        }
        return $this->companyUuid;
    }
    public function resolved(){
        return $this->companyId !== null;
    }
    

}
?>