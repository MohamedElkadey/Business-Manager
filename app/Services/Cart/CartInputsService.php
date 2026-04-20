<?php 

use App\Models\CartItem;
use App\Models\CartItemPricingInput;
use App\Tenancy\TenantContext;
class CartInputsService{
    public function __construct(protected TenantGuard $tenantGuard , protected TenantContext $tenant,
       protected TemplatePricingInputService $templatePricingInputService  ){}

    private function supportCartItem(CartItem $item){
        $this->tenantGuard->checkId($item->company_id);
        $cart = $item->cart;
        if($cart->status != "active")
            throw new DomainException('invalid Item');
    }
    public function addAllInputs(CartItem $item , array $inputKeysAndValues){
        return DB::transaction(function () use ($item , $inputKeysAndValues) {
            $this->supportCartItem($item);
            $inputs = $this->templatePricingInputService
                ->getInputs($item->product->template)
                ->keyBy('key');
            if($inputs->isEmpty()|| count($inputKeysAndValues) !== $inputs->count()){
                throw new DomainException('error while checking Inputs');
            }
            $data = [];
            foreach ($inputKeysAndValues as $key => $value) {
                if (!isset($inputs[$key])) {
                    throw new DomainException('Invalid Input Label');
                }
                $input = $inputs[$key];
                $this->templatePricingInputService->validateValue($input, $value);
                $type = $input->input_type;
                $data[] = [
                    'pricing_template_input_id' => $input->id,
                    'cart_item_id'              => $item->id,
                    'value_number'              => $type === "number" ? $value : null,
                    'value_text'                => $type === "string" ? $value : null,
                    'value_boolean'             => $type === "boolean" ? $value : null,
                    'value_date'                => $type === "date" ? $value : null,
                    'datetime'                  => $type === "datetime" ? $value : null,
                    'value_json'                => $type === "json" ? $value : null,
                ];
            }
            return CartItemPricingInput::insert($data);
        });
    }
    public function updateInput(CartItemPricingInput $input , $newv){
        DB::transaction(function ()use($input , $newv){
            $this->supportCartItem($input->cartItem);
            if(gettype($this->getValue($input)) != gettype($newv))
                throw new DomainException('invalid Input');
            $type = $this->gettype($input);
            switch ($type){
                case "string":
                    return $input->update(['value_string' => $newv]);
                case "number":
                    return $input->update(['value_number' => $newv]);
                case "boolean":
                    return $input->update(['value_boolean' => $newv]);
                case "date":
                    return $input->update(['value_date' => $newv]);
                case "datetime":
                    return $input->update(['value_datetime' => $newv]); 
            }
        });

    }
    private function gettype(CartItemPricingInput $input){
        return $input->pricingTemplateInput->input_type;
    }
    public function getAll(CartItem $cartItem){
        $this->supportCartItem($cartItem);
        return $cartItem->pricingInputs()->with('pricingTemplateInput')->get();   
    }
    private function getValue(CartItemPricingInput $input){
        // $this->supportCartItem($input->cartItem);
        $type = $this->gettype($input);
        return match ($type) {
            'number' => $input->value_number,
            'string' => $input->value_text,
            'boolean' => $input->value_boolean,
            'date' => $input->value_date,
            'datetime' => $input->value_datetime,
            default => null,
        };
    }
    public function deleteInputs(CartItem $cartItem){
        $this->supportCartItem($cartItem);
        
    }
    public function snpshotInputs(CartItem $cartItem){
        $this->supportCartItem($cartItem);
        $snap = [];
        $inputs = $this->getAll($cartItem);
        foreach($inputs as $input){
            $snap[] =[
                'id' => $input['id'],
                'type' => $this->gettype($input),
                'value' => $this->getValue($input),
                'updated_at' => $input->updated_at,
            ];
        }
        return $snap;
    }
    
}
?>