<?php
namespace App\Tenancy;

use App\Models\PosSession;
use DateException;
use DomainException;
 
class TenantContext{
    private ?int $companyId = null;
    private ?string $companyUuid = null;
    private ?int $user_id = null;
    private ?PosSession $pos_session = null;
    public function set(int $companyId , string $companyUuid){
        $this->companyId = $companyId;
        $this->companyUuid = $companyUuid;
    }
    public function setuser(int $user){
        $this->user_id = $user;
    }
    public function setPosSession(PosSession $pos_session){
        $this->pos_session = $pos_session;
    }
    public function getPosSession() {
        if (!$this->pos_session) {
            throw new DomainException('POS session not set.');
        }
        return $this->pos_session;
    }
    public function hasPosSession(): bool {
        return $this->pos_session !== null;
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