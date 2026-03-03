<?php 

use App\Models\Company;
use App\Models\Template;
use App\Tenancy\TenantContext;
class TemplateCreateService{
    public function __construct(private TenantContext $tenant , private TenantGuard $tenantGuard){}

    public function create( array $data): Template
    {
        return Template::create([
            'company_id' => $this->tenant->getCompanyId(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'expression_type' => $data['expression_type'] ?? 'fixed',
            'expression' => ($data['expression_type'] == 'fixed')? ($data['expression'] ?? null) : null,
            'pricing_version' => 1,
            'parent_template_id' => null,
            'is_active' => true,
            'status' => 'draft',
        ]);
    }
    
    public function update(Template $template,array $data){
        $template->update([
            'name'          => $data['name'] ?? $template->name,
            'description'   => $data['description'] ?? $template->description,

        ]);
    }

    public function clone(Template $template){
        if ($template->status !== 'published') {
            throw new DomainException('Only published templates can be cloned.');
        }
        return DB::transaction(function() use ($template) {
            $cloned = $template->replicate();
            $cloned->name = $template->name . ' Copy';
            $cloned->pricing_version = 1;
            $cloned->parent_template_id = $template->id;
            $cloned->status = 'draft';

            $cloned->save();
            // Clone fields
            app(TemplateFieldService::class)->cloneFields($template, $cloned);
            // Clone variant attributes and options
            app(TemplateVarainceService::class)->cloneVariantDefinitions($template, $cloned);
            // Clone Price Inpute Fields
            app(TemplatePricingInputService::class)->cloneInputs($template , $cloned);
            return $cloned->fresh();
        });

    }
    public function getTemplates(){
        $this->tenantGuard->checkId($this->tenant->getCompanyId());
        return Template::where('company_id',$this->tenant->getCompanyId())->get();
    }

}



?>