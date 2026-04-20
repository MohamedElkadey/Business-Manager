<?php 

use App\Models\Cart;
use App\Models\Custmer;
use App\Models\PosSession;
use App\Tenancy\TenantContext;
use Illuminate\Validation\ValidationException;
class CartService{
    public function __construct(protected TenantGuard $tenantGuard , protected TenantContext $tenant){}

    public function getOrCreateActiveCartByCustomer(Custmer $customer ): Cart
    {
        $companyId = $this->tenant->getCompanyId();
        $cart = Cart::where('company_id', $companyId)
            ->where('customer_id', $customer->id)
            ->where('status', 'active')
            ->lockForUpdate()
            ->first();
        if ($cart) {
            return $cart;
        }
        
        return Cart::create([
            'company_id'  => $companyId,
            'customer_id' => $customer->id,
            'status'      => 'active',
            'pos_session_id' => $this->tenant->getPosSession()?->id
        ]);
        
        
    }
    public function getOrCreateActiveCartByPosSession(): Cart{
        $posSession = $this->tenant->getPosSession();
        $company_id = $this->tenant->getCompanyId();
        $cart = Cart::firstOrCreate([
            'company_id'    => $company_id,
            'pos_session_id'=> $posSession->id,
            'status'        => 'active',
        ]);
        return $cart;
    }
    public function PosHaveCart(){
        return Cart::where([
            'company_id'    => $this->tenant->getCompanyId(),
            'pos_session_id'=> $this->tenant->getPosSession()->id,
            'status'        => 'active',
        ])->exists();
    }
    public function openCustomerCartInPos(Custmer $customer): Cart{
        $companyId = $this->tenant->getCompanyId();
        $posSession = $this->tenant->getPosSession();

        return DB::transaction(function () use ($companyId, $posSession, $customer) {

            $posCart = Cart::where('company_id', $companyId)
                ->where('status', 'active')
                ->where('pos_session_id', $posSession->id)
                ->first();

            if ($posCart) {
                $posCart->update([
                    'status' => 'canceled'
                ]);
            }

            $cart = Cart::firstOrCreate([
                'company_id'  => $companyId,
                'customer_id' => $customer->id,
                'status'      => 'active',
            ]);

            if ($cart->pos_session_id !== $posSession->id) {
                $cart->update([
                    'pos_session_id' => $posSession->id
                ]);
            }

            return $cart;
        });
    }
    public function confirm(Cart $cart): Cart
    {
        $this->tenantGuard->checkId($cart->company_id);

        if ($cart->status !== 'active') {
            throw ValidationException::withMessages([
                'cart' => 'Cart is not active.'
            ]);
        }

        if ($cart->items()->count() === 0) {
            throw ValidationException::withMessages([
                'cart' => 'Cannot confirm empty cart.'
            ]);
        }

        $cart->status = 'confirmed';
        $cart->save();

        return $cart;
    }
    public function is_active(Cart $cart){
        return $cart->status == 'active'; 
    }
    public function sh_active(Cart $cart){
        if(!$this->is_active($cart)){
            throw new DomainException('Invalid Cart');
        }
    }
}
?>