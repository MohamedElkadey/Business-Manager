<?php 

use App\Models\Company;
use App\Models\Template;
use App\Models\VariantAttributeOption;
use App\Tenancy\TenantContext;
use Illuminate\Support\Str;
use App\Models\VariantAttributeValue;
class TemplateVarainceService{
    public function __construct(private TenantContext $tenant , private TenantGuard $tenantGuard){}

    public function createAtribute( Template $template, array $data){
        $this->validate($template);
        return VariantAttributeValue::create([
            'company_id'    => $this->tenant->getCompanyId(),
            'template_id'   => $template->id,
            'name'          => $data['name'],
            'key'           => $this->generateKey($data['name'], $template),
        ]);
    }

    private function generateKey(string $name , Template $template){
        $base = Str::slug($name, '_');
        $key = $base;
        $counter = 1;
        while(VariantAttributeValue::where('template_id', $template->id)->where('key' , $key)->exists()){
            $key = $base . '_' . $counter;
            $counter++;
        }
        return $key;
    }

    public function updateAtribute(VariantAttributeValue $attribute, array $data){
        $this->validate($attribute->template);
        $attribute->update([
            'name' => $data['name'] ?? $attribute->name,
        ]);
        return $attribute->fresh();
    }

    public function deleteAtribute(VariantAttributeValue $attribute){
        app(TemplatePublishService::class)->sh_draft($attribute->template);
        $attribute->delete();
    }
    public function addOption(VariantAttributeValue $attribute, string $optionValue){
        $this->validate($attribute->template);
        $this->validateOptionValueExists($attribute, $optionValue);
        return $attribute->options()->create([
            'value' => $optionValue,
        ]);
    }
    private function validateOptionValueExists(VariantAttributeValue $attribute, string $optionValue){
        if($attribute->options->where('value', $optionValue)->exists()){
            throw new DomainException('Option value already exists.');
        }
    }
    public function deleteOption(VariantAttributeOption $option){
        $this->validate($option->attribute->template);
        $option->delete();
    }
    private function validate(Template $template){
        $this->tenantGuard->checkId($template->company_id);
        app(TemplatePublishService::class)->sh_draft($template);
    }

    public function cloneVariantDefinitions(Template $original, Template $cloned){
        DB::transaction(function () use ($original, $cloned) {

            $attributeMap = [];

            foreach ($original->variantAttributes as $attribute) {

                $newAttribute = $attribute->replicate();
                $newAttribute->template_id = $cloned->id;
                $newAttribute->save();

                $attributeMap[$attribute->id] = $newAttribute->id;
            }

            foreach ($original->variantAttributes as $attribute) {
                foreach ($attribute->options as $option) {

                    $newOption = $option->replicate();
                    $newOption->variant_attribute_id =
                        $attributeMap[$option->variant_attribute_id];
                    $newOption->save();
                }
            }
        });
    }


}


?>