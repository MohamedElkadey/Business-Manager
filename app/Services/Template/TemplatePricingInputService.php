<?php 

use App\Models\PricingTemplateInput;
use App\Models\Template;
use App\Tenancy\TenantContext;
class TemplatePricingInputService{
    public function __construct(private TenantGuard $tenantGuard, private TenantContext $tenant){}

    public function createInput(Template $template , array $data){
        $this->InputSupport($template);
        return  PricingTemplateInput::create([
            'template'          => $template->id,
            'key'               => $this->generateKey($template,$data['label']),
            'label'             => $data['label'],
            'input_type'        => $data['input_type'],
            'unit'              => $data['unit']?? null,
            'options'           => $data['options']?? null,
            'validation_rules'  => $data['validation_rules']??null,  
        ]);

    }
    private function InputSupport(Template $template){
        $this->tenantGuard->checkId($template->company_id);
        app(TemplatePublishService::class)->sh_draft($template);
    }
    private function generateKey(Template $template,string $name){
        $base = Str::slug($name,'-');
        $key = $base;
        $count = 1;
        while(PricingTemplateInput::when('template_id' , $template->id)->where('key' , $key)->exists() ){
            $key = $base . '_' . $count;
            $count++;
        }
        return $key;
    }
    public function updateInput(PricingTemplateInput $pricingInput , array $data){
        $this->InputSupport($pricingInput->template);
        return $pricingInput->update([
            'label'     => $data['label']??$pricingInput->label,
            'options'   => $data['options']??$pricingInput->options,
            'unit'      => $data['unit']??$pricingInput->unit,
            'validation_rules' => $data['validation_rules']??$pricingInput->validation_rules,
        ]);
    }
    public function getInputByKey(Template $template , string $key){
        return $template->pricingTemplateInput->where('key' , $key);
    }
    public function getInputByID(Template $template , int $id){
        return $template->pricingTemplateInput->where('id' , $id);
    }
    public function deleteInput(PricingTemplateInput $priceInpute){
        $this->InputSupport($priceInpute->template);
        return $priceInpute->delete();
    }
    public function cloneInputs(Template $original , Template $cloned){
        $inputs = $original->pricingTemplateInput;
        foreach($inputs as $inp){
            $newInput = $inp->replicate();
            $newInput->template_id = $cloned;
            $newInput->save();
        }
    }
    
}
?>