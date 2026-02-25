<?php 

use App\Models\Company;
use App\Models\Product;
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
    private function assertProductSupports(Template $template){
        $this->tenantGuard->checkId($template->company_id);

        if(!app(TemplatePublishService::class)->is_published($template)){
            throw new DomainException('Template must be published to create a product.');
        }

    }
}

?>