<?php 

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Tenancy\TenantContext;
class CartItemService{
    public function __construct(protected TenantGuard $tenantGuard , protected TenantContext $tenant,
        private CartService $cartService){}

    public function findOrAddItem(Cart $cart,Product $product ,?ProductVariant $variant ,?float $quantity,?array $pricingInputs = [] ){
        $this->cartItemSupport($cart ,$product,$variant);
        return DB::transaction(function () use ($cart, $product, $variant, $quantity, $pricingInputs) {

        $cart->lockForUpdate();

        $item = $this->getItem($cart,$product,$variant);

        if ($item) {
            $item =  $this->updateItem($item , ['quantity'=>$quantity]);
            return $item;
        }

        $pricing = app(ProductPriceService::class)->getPrice($product, $variant?->id, $pricingInputs); // should return snapshot
            $price = $pricing['price'];
            $pricing['quantity'] = $quantity;
            $cart_item = CartItem::create([
                'company_id'        => $cart->company_id,
                'cart_id'           => $cart->id,
                'product_id'        => $product->id,
                'pricing_version'   => $product->pricing_version,
                'product_variant_id'=> $variant?->id,
                'quantity'          => $quantity,
                'price'             => $price,
                'pricing_snapshot'  => $pricing,
            ]);
            return $cart_item;
        });
    }
    private function cartItemSupport(Cart $cart , ?Product $prduct, ?ProductVariant $variant){
        $this->tenantGuard->checkId($cart->company_id);
        $this->cartService->sh_active($cart);    
        if($prduct)
            $this->tenantGuard->checkId($prduct->company_id);
        if($variant){
            $this->tenantGuard->checkId($variant->company_id);
            $this->tenantGuard->checkIdEqual($variant->product_id , $prduct->id);
        }

    }
    public function getItem(Cart $cart , Product $product, ?ProductVariant $variant ){
        $this->cartItemSupport($cart , $product , $variant);
        $item = $cart->cartItems()
            ->where('product_id', $product->id)
            ->when($variant, fn($q) => $q->where('product_variant_id', $variant->id))
            ->first();
        return $item;
    }
    private function updatePrice(CartItem $item , array $newPricing){
        // ToDo edit snapshot layout
        return $item->update([
            'price'         => $newPricing['price'],
            'pricing_version'=> $item->product->pricing_version,
            'pricing_snapshot'=> $newPricing,
        ]);
    }
    public function checkPriceVersion(CartItem $item){
        $this->tenantGuard->checkId($item->company_id);
        if($item->pricing_version != $item->product->pricing_version){
            $newPricing = app(ProductPriceService::class)->getPrice($item->product,$item->product_variant_id , $item->pricing_snapshot['inputs']);
            $this->updatePrice($item , $newPricing);
        }
        return $item;
    }
    public function updateItem(CartItem $item,array $data){
        return DB::transaction(function () use ($item, $data) {
            $item->lockForUpdate();
            $quantity = $data['quantity'];
            if ($quantity <= 0) {
                $item->delete();
                return null;
            }
            $item->update([
                'quantity' => $quantity,
            ]);
            return $item;
        });
    }
    public function deleteItem(CartItem $item){
        $this->cartItemSupport($item->cart , $item->product, $item->productVariant);
        $item->delete();
    }
    public function getAllItems(Cart $cart){
        $this->cartItemSupport($cart,null ,null);
        return $cart->cartItems()->get();
    }
}
?>