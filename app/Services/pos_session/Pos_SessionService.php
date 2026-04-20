<?php

use App\Models\PosSession;
use App\Tenancy\TenantContext;
class Pos_SessionService{
    public function __construct(
        protected TenantGuard $tenantGuard,
        protected TenantContext $tenant
    ){}

    public function getOrCreateActiveSession(int $companyId, int $userId, string $deviceUuid){
        $session =  PosSession::firstOrCreate([
            'company_id'    => $companyId,
            'user_id'       => $userId,
            'device_uuid'   => $deviceUuid,
            'status'        => 'active'
        ],[
            'opend_at'  => now(),
            'last_activity_at'  => now()
        ]);
        if(!$session->wasRecentlyCreated){
            $session->update([
                'last_activity_at' => now()
            ]);
        }
        return $session;
    }
    public function getActiveSession(int $companyId, int $userId, string $deviceUuid){
        return PosSession::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('device_uuid', $deviceUuid)
            ->where('status', 'active')
            ->first();
    }
    public function closeSession(PosSession $session)
    {
        if ($session->status === 'closed') {
            throw new DomainException('Session is already closed.');
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
    public function touchActivity(PosSession $session): void
    {
        if ($session->status !== 'active') {
            throw new DomainException('Cannot touch activity of closed session.');
        }

        $session->update([
            'last_activity_at' => now(),
        ]);
    }
}
?>