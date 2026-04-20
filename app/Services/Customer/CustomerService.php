<?php

namespace App\Services;

use App\Models\Custmer;
use App\Tenancy\TenantContext;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use TenantGuard;

class CustomerService
{
    public function __construct(
        protected TenantContext $tenant,
        protected TenantGuard $tenantGuard
    ) {}

    protected function companyId(): int
    {
        return $this->tenant->getCompanyId();
    }

    public function create(array $data): Custmer
    {
        $companyId = $this->companyId();

        if (Custmer::where('company_id', $companyId)
            ->where('phone', $data['phone'])
            ->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Phone already exists for this company.'
            ]);
        }

        return Custmer::create([
            'company_id' => $companyId,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ,
            'address'    => $data['address'] ?? null,
            'metadata'   => $data['metadata'] ?? [],
        ]);
    }
    public function update(Custmer $customer, array $data): Custmer
    {
        $this->ensureTenantOwnership($customer);

        $customer->fill([
            'name'     => $data['name'] ?? $customer->name,
            'phone'    => $data['phone'] ?? $customer->phone,
            'address'  => $data['address'] ?? $customer->address,
            'metadata' => $data['metadata'] ?? $customer->metadata,
        ]);

        $customer->save();

        return $customer;
    }

    public function findByPhone(string $phone): ?Custmer
    {
        return Custmer::where('company_id', $this->companyId())
            ->where('phone', $phone)
            ->first();
    }

    public function resolveForCheckout(array $data): Custmer
    {
        $customer = $this->findByPhone($data['phone']);

        if ($customer) {
            return $customer;
        }

        return $this->create($data);
    }

    protected function ensureTenantOwnership(Custmer $customer): void
    {
        $this->tenantGuard->checkId($customer->company_id);
    }
}