<?php 

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Template;
use App\Tenancy\TenantContext;
class ProductPriceService{
    public function __construct(private TenantGuard $tenantGuard ,private TenantContext $tenant){}

    public function check_price_type(Product $product ){
        $template = $product->template;
        switch ($template->expression_type){
            case "fixed":
                $this->fixedPriceSupport($product);
                break;
            case "expression":
                $this->expressionPriceSupport($product);
                break;
            case "input_base_exp":
                // ToDo
                break;
            default:
                throw new DomainException("invalid expression type");
        }
        return $product->fresh();
    }
    public function fixedPriceSupport(Product $product){
        if(!is_null($product->expression)){
            $product->update([
                'expression' => null
            ]);
        }
        if(!is_numeric($product->base_rate)){
            $product->update([
                'base_rate' => 0,
            ]);
        }
    }
    
    private function expressionPriceSupport(Product $product){
        // ToDo
    }
    private function expressionPrice(Product $product){
        // ToDo
        return 1;
    }
    private function getPriceOverrided(ProductVariant $variant , array $inputs){
        $p = $variant->price_override;
        if(is_null($p)){
            $snapshot =  $this->priceCalc((float)$variant->product->base_rate, $variant->product , $inputs);
        }else
            $snapshot = $this->priceCalc((float)$p, $variant->product,$inputs);
        
        $snapshot = array_merge($snapshot,['variant' => $variant->id  ]);
        return $snapshot;
    }
    private function priceCalc(float $base_rete,Product $product , array $inputs){
        $template = $product->template;
        switch ($template->expression_type){
            case "fixed":
                return ['price' => $base_rete , 'type' => $template->expression_type ];
            case "expression":
                $price = $this->expressionPrice($product);
                return ['price' => $price , 'type' => $template->expression_type , 'expression' => $template->expression  ];
            case "input_base_exp":
                // ToDo
                break;
            default:
                throw new DomainException("invalid expression type");
        }
    }

    public function getPrice(Product $product ,int $varintId = null , array $input_base = null){
        $this->tenantGuard->checkId($product->company_id);
        $varIds = collect(app(ProductVarianceService::class)->getVariants($product))->pluck('id')->toArray();
        if(!empty($varIds)){
            if(!is_null($varintId) && in_array($varintId,$varIds)){
                $v = app(ProductVarianceService::class)->getVariantById($varintId);
                $snapshot = $this->getPriceOverrided($v, $input_base);
            }else{
                throw new DomainException('varient not found');
            }
        }else
            $snapshot = $this->priceCalc((float)$product->base_rate,$product, $input_base);
        
        $template = $product->template;
        $snapshot = array_merge($snapshot , ['product_id' => $product->id ,'template_id' => $template->id ,'pricing_version' => $template->pricing_version  ]);

        return $snapshot;
    }
}
?>