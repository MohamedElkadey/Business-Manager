<?php

use App\Tenancy\TenantContext;
class TenantGuard{
    public function __construct(private TenantContext $tenant ){}
    public function checkId(int $companyId){
        if($this->tenant->getCompanyId() !== $companyId){
            throw new DomainException('Invalid tenant access.');
        }
    }
}


?>