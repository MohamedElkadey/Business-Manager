<?php 
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Template;
use App\Models\VariantAttributeOption;
use App\Models\VariantOptionValue;
use App\Tenancy\TenantContext;
class ProductVarianceService {
    public function __construct(private TenantContext $tenant, private TenantGuard $tenantGuard){}

    public function createVariantManully(Product $product, array $attributesOptions){
        $this->tenantGuard->checkId($product->company_id);
        $this->assertProductSupportsVariants($product);
        $template = $product->template;
        $attributes_options = $this->getAttributeOptions($template);
        foreach($attributes_options as $attribute) {
            if ($attribute->options->isEmpty()) {
                throw new DomainException("Attribute {$attribute->name} has no options.");
            }
        }
        if(count($attributesOptions) !== $attributes_options->count()) {
            throw new DomainException('You must provide exactly one option for each attribute.');
        }
        if(count($attributesOptions) !== count(array_unique(array_keys($attributesOptions)))) {
            throw new DomainException('Duplicate attributes are not allowed.');
        }
        $optionIds = [];
        foreach($attributes_options as $attribute){
            $attId = $attribute->id;
            if(!isset($attributesOptions[$attId])){
                throw new DomainException("Missing option for attribute: {$attribute->name}");
            }
            $optionId = $attributesOptions[$attId];
            $optionexists = $attribute->options->where('id', $optionId)->isNotEmpty();
            if(!$optionexists){
                throw new DomainException("Invalid option for attribute: {$attribute->name}");
            }
            $optionIds[] = $optionId;
        }
        sort($optionIds);
        $combinationKey = implode('-', $optionIds);
        return DB::transaction(function() use ($product, $combinationKey , $attributesOptions){
            $variant = ProductVariant::create([
                'company_id' => $product->company_id,
                'product_id' => $product->id,
                'sku' => $product->sku . '-'. substr(md5($combinationKey,false), 0, 6),
                'combination_key' =>  $combinationKey,
                'price_override' => null,
                'stock_quantity' => 0,
                'is_active' => true,
            ]);
            foreach($attributesOptions as $attribute => $option){
                VariantOptionValue::create([
                    'product_variant_id' => $variant->id,
                    'variant_attribute_option_id' => $option,
                    'variant_attribute_id' => $attribute,
                ]);
            }
            return $variant->fresh();
        });


    }
    public function getAttributeOptions(Template $template){
        $attributes_options = $template->variantAttributes()
            ->with(['options:id,variant_attribute_id,value'])
            ->get();
        return $attributes_options;
    }
    public function generateAllVariants(Product $product)
    {
        $this->assertProductSupportsVariants($product);
        $template = $product->template;

        $attributes = $this->getAttributeOptions($template);

        $optionsArrays = $attributes->map(function ($attribute) {
            return $attribute->options->map(function ($option) use ($attribute) {
                return [
                    'attribute_id' => $attribute->id,
                    'option_id' => $option->id,
                ];
            })->toArray();
        })->toArray();

        $total = $this->calculateTotalCombinations($optionsArrays);

        if ($total > 500) {
            throw new DomainException("Too many combinations: {$total}");
        }

        DB::transaction(function () use ($product, $optionsArrays) {

            $this->generateCombinations($optionsArrays, [], 0, function ($combination) use ($product) {

                $optionIds = array_column($combination, 'option_id');
                sort($optionIds);
                $combinationKey = implode('-', $optionIds);

                try {

                    $variant = ProductVariant::create([
                        'company_id' => $product->company_id,
                        'product_id' => $product->id,
                        'sku' => $product->sku . '-' . substr(md5($combinationKey,false), 0, 6),
                        'combination_key' => $combinationKey,
                        'stock_quantity' => 0,
                        'is_active' => true,
                    ]);

                    foreach ($combination as $item) {
                        VariantOptionValue::create([
                            'product_variant_id' => $variant->id,
                            'variant_attribute_id' => $item['attribute_id'],
                            'variant_attribute_option_id' => $item['option_id'],
                        ]);
                    }

                } catch (\Illuminate\Database\QueryException $e) {
                    // Duplicate combination — ignore safely
                }
                
            });

        });
    }
    public function updateVariant(ProductVariant $variant, array $data){
        $this->tenantGuard->checkId($variant->company_id);
        
        $variant->update([
            'stock_quantity' => $data['stock_quantity'] ?? $variant->stock_quantity,
            'is_active' => $data['is_active'] ?? $variant->is_active,
        ]);
        DB::transaction(function () use ($variant , $data){
            if(!is_null($data['price_override']) && !is_null($variant->price_override)){
                if($data['price_override'] !== $variant->price_override){
                    $variant->product->pricing_version++;
                }
            }
            $variant->update(['price_override' => $data['price_override']]);
        });
        return $variant->fresh();
    }
    public function getVariants(Product $product){
        $this->tenantGuard->checkId($product->company_id);
        
        return $product->variants()->with(['optionValues.variantAttribute', 'optionValues.variantAttributeOption'])->get();
    }
    public function getVariantById(int $variantId){
        $companyId = $this->tenant->getCompanyId();
        return ProductVariant::where('company_id', $companyId)
            ->with(['optionValues.variantAttribute', 'optionValues.variantAttributeOption'])
            ->firstOrFail($variantId);
    }
    private function generateCombinations(array $arrays, array $current = [], int $index = 0, callable $callback)
    {
        if ($index === count($arrays)) {
            $callback($current);
            return;
        }

        foreach ($arrays[$index] as $item) {
            $this->generateCombinations(
                $arrays,
                array_merge($current, [$item]),
                $index + 1,
                $callback
            );
        }
    }
    private function calculateTotalCombinations(array $optionsArrays): int
    {
        $total = 1;

        foreach ($optionsArrays as $options) {
            $total *= count($options);
        }

        return $total;
    }
    private function assertProductSupportsVariants(Product $product): void
    {
        $this->tenantGuard->checkId($product->company_id);

        $template = $product->template;

        if (!$template) {
            throw new DomainException('Product has no template.');
        }
        $this->tenantGuard->checkId($template->company_id);

        app(TemplatePublishService::class)->sh_published($template);

        if ($template->variantAttributes()->count() === 0) {
            throw new DomainException('Template does not support variants.');
        }
    }

}
?>