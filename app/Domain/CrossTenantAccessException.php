<?php 
class CrossTenantAccessException extends DomainException {
    protected $message = 'You cannot access this resource.';
    
}
?>