<?php 

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Template;
use App\Tenancy\TenantContext;
class ProductService{
    public function __construct( private TenantContext $tenant, private TenantGuard $tenantGuard ){}

    public function createProduct(Template $template, array $data){
        $this->assertProductSupports($template);
        $companyId = $this->tenant->getCompanyId();
        return DB::transaction(function() use ($companyId, $template, $data){
            $product = Product::create([
                'company_id' => $companyId,
                'template_id' =>$template->id,
                'name' =>$data['name'],
                'description' => $data['description'] ?? null,
                'sku'  => $this->generateUniqueSku($template, $data['name']),
                'is_active' => true,
                'base_rate' => $data['base_rate'] ?? 0,
                'extra' => $data['extra'] ?? null,
            ]);
            $product = app(ProductPriceService::class)->check_price_type($product);
            app(ProductFieldValueService::class)->createAll($product, $data['field_values']);
            return $product->fresh();
            
        });
    }
    private function generateUniqueSku(Template $template, string $name){
        $baseSku = Str::slug($name, '-');
        $sku = $baseSku;
        $counter = 1;
        while(Product::where('template_id', $template->id)->where('sku', $sku)->exists()){
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }
        return $sku;
    }
    
    public function updateProduct(Product $product , $data){
        $this->tenantGuard->checkId($product->company_id);
        return DB::transaction(function () use ($product,$data){
            $product->update([
                'name' =>$data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'is_active' => $data['is_active'] ?? $product->is_active,
                'base_rate' => $data['base_rate'] ?? $product->base_rate,
                'extra' => $data['extra'] ?? $product->extra,
            ]);
            foreach($data['field_values'] as $fv){
                app(ProductFieldValueService::class)->updateProductFieldValue($product,$fv['field_id'] , $fv['value']);
            }

        });
    }

    
    private function assertProductSupports(Template $template){
        $this->tenantGuard->checkId($template->company_id);
        app(TemplatePublishService::class)->sh_published($template);

    }
    public function getProducts(Template $template){
        $this->tenantGuard->checkId($template->company_id);
        app(TemplatePublishService::class)->sh_published($template);
        return $template->products()->with('fieldValues')->get();
    }
    public function getProduct(Template $template , int $productId){
        $this->tenantGuard->checkId($template->company_id);
        app(TemplatePublishService::class)->sh_published($template);
        return $template->products()->with('fieldValues')->findOrFail($productId);
    }

}

?>