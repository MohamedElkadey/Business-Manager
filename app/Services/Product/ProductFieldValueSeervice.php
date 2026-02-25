<?php 

use App\Models\Field;
use App\Models\Product;
use App\Models\ProductFieldValue;
use App\Tenancy\TenantContext;
class ProductFieldValueService{
    public function __construct(private TenantContext $tenant, private TenantGuard $tenantGuard){}

    public function addProductFieldValue(Product $product, int $fieldId, $value){
        $field = $this->assetProductSupportsField($product,$fieldId);
        if(!$field){
            throw new DomainException('Field not supported by product template.');
        }
        $value = $this->checkinputValue($field , $value);
        $val =  ProductFieldValue::create([
            'product_id' => $product->id,
            'field_id' => $fieldId,
            'value_number' => $field->field_type === 'number' ? $value : null,
            'value_text' => $field->field_type === 'string' ? $value : null,
            'value_boolean' => $field->field_type === 'boolean' ? $value : null,
            'value_date' => $field->field_type === 'date' ? $value : null,
            'value_datetime' => $field->field_type === 'datetime' ? $value : null,
            'value_json' => in_array($field->field_type , ['select','json']) ? json_encode($value) : null,
        ]);
        return $val;
    }
    private function checkinputValue(Field $field , $value){
        if($field->required && is_null($value)){
            throw new DomainException('This field is required.');
        }elseif(!is_null($value)  ){
            app(TemplateFieldService::class)->validateDefaultValue($field , $value);
        }elseif(!is_null($field->default_value)){
            $value = $field->default_value;
        }else{
            $value = null;
        }
        return $value;
    }
    private function assetProductSupportsField(Product $product ,  int $fieldId){
        $this->tenantGuard->checkId($product->company_id);
        $field = app(TemplateFieldService::class)->getField($product->template,$fieldId);
        return $field;
    }
    public function getProductFieldValues(Product $product){
        $this->tenantGuard->checkId($product->company_id);
        return $product->fieldValues()->with('field')->get();
    }
    public function getProductFieldValue(Product $product , int $fieldValueId){
        $this->tenantGuard->checkId($product->company_id);
        $fieldValue = ProductFieldValue::where('product_id',$product->id)->findOrFail($fieldValueId);
        $fieldValue->load('field');
        return $fieldValue;
    }
    public function updateProductFieldValue(Product $product , int $fieldValueId , $value){
        $fieldValue = $this->getProductFieldValue($product,$fieldValueId);
        $field = $fieldValue->field;
        $value = $this->checkinputValue($field , $value);
        $fieldValue->update([
            'value_number' => $field->field_type === 'number' ? $value : null,
            'value_text' => $field->field_type === 'string' ? $value : null,
            'value_boolean' => $field->field_type === 'boolean' ? $value : null,
            'value_date' => $field->field_type === 'date' ? $value : null,
            'value_datetime' => $field->field_type === 'datetime' ? $value : null,
            'value_json' => in_array($field->field_type , ['select','json']) ? json_encode($value) : null,
        ]);
        return $fieldValue;
    }
    public function createAll(Product $product , array $fieldValues ){ // [field_id , value]
        $createdValues = [];
        DB::transaction(function() use ($product , $fieldValues , &$createdValues){
            foreach($fieldValues as $fieldV){
                $createdValues[] = $this->addProductFieldValue($product , $fieldV['field_id'], $fieldV['value']);
            }
        });
        return $createdValues;
    }
    

}
?>