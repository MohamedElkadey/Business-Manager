<?php

use App\Models\Field;
use App\Models\Template;
use DomainException;

class TemplateFieldService
{
    protected array $validTypes = [
        'string',
        'number',
        'boolean',
        'select',
        'date',
        'datetime',
    ];

    public function createMany(Template $template, array $fields): array
    {
        $this->assertTemplateEditable($template);

        $createdFields = [];
        $maxPosition = $template->field()->max('position') ?? 0;
        foreach ($fields as $fieldData) {

            if (!in_array($fieldData['field_type'], $this->validTypes)) {
                throw new DomainException("Invalid field type: {$fieldData['field_type']}");
            }

            if ($fieldData['field_type'] === 'select' && empty($fieldData['options'])) {
                throw new DomainException("Select type field must have non-empty options.");
            }

            $field = Field::create([
                'template_id' => $template->id,
                'label' => $fieldData['label'],
                'field_type' => $fieldData['field_type'],
                'unit' => $fieldData['unit'] ?? null,
                'required' => $fieldData['required'],
                
                'default_value' => $fieldData['default_value'] ?? null,
                'options' => $fieldData['options'] ?? null,
                'position' => $maxPosition + 1,
            ]);
            $maxPosition++;
            $this->validateDefaultValue($field, $field->default_value);

            $createdFields[] = $field;
        }

        return $createdFields;
    }

    public function update(Field $field, array $data): Field
    {
        $this->assertTemplateEditable($field->template);
        $this->assertFieldTypeImmutable($field, $data['field_type']);

        // For select type, ensure default_value is in new options
        if ($field->field_type === 'select' && isset($data['options'])) {
            if (!is_array($data['options'])) {
                throw new DomainException("Options must be an array.");
            }
            if (!is_null($data['default_value']) && !in_array($data['default_value'], $data['options'])) {
                throw new DomainException("Default value must be one of the new options.");
            }
        }

        $field->update([
            'label' => $data['label'],
            'unit' => $data['unit'] ?? null,
            'required' => $data['required'],
            'default_value' => $data['default_value'] ?? null,
            'options' => $data['options'] ?? $field->options,
        ]);

        $this->validateDefaultValue($field, $field->default_value);

        return $field->fresh();
    }

    public function delete(Field $field): void
    {
        $this->assertTemplateEditable($field->template);
        $field->delete(); 
    }

    private function assertTemplateEditable(Template $template): void
    {
        if ($template->status !== 'draft') {
            throw new DomainException('Cannot modify fields of a non-draft template.');
        }
    }

    private function assertFieldTypeImmutable(Field $field, string $newType): void
    {
        if ($newType !== $field->field_type) {
            throw new DomainException('Field type cannot be changed.');
        }
    }

    private function validateDefaultValue(Field $field, $defaultValue): void
    {
        if (is_null($defaultValue)) return;

        switch ($field->field_type) {
            case 'string':
                if (!is_string($defaultValue) || strlen($defaultValue) > 255) {
                    throw new DomainException('Default value must be a string <= 255 characters.');
                }
                break;
            case 'number':
                if (!is_numeric($defaultValue)) {
                    throw new DomainException('Default value must be numeric.');
                }
                break;
            case 'boolean':
                if (!is_bool($defaultValue)) {
                    throw new DomainException('Default value must be boolean.');
                }
                break;
            case 'select':
                if (!in_array($defaultValue, $field->options ?? [])) {
                    throw new DomainException('Default value must be one of the options.');
                }
                break;
            case 'date':
            case 'datetime':
                if (strtotime($defaultValue) === false) {
                    throw new DomainException("Default value must be a valid {$field->field_type}.");
                }
                break;
        }
    }


    public function reorder(Template $template, array $orderedFieldIds){

        $fields = $template->field()->whereIn('id', $orderedFieldIds)->get();

        if (count($fields) !== count($orderedFieldIds)) {
            throw new DomainException('Some field IDs are invalid for this template.');
        }

        foreach ($orderedFieldIds as $index => $fieldId) {
            $field = $fields->firstWhere('id', $fieldId);
            $field->update(['position' => $index]);
        }
    }

    public function cloneFields(Template $original, Template $cloned): void{
        $original->field()->get()->each(function ($field) use ($cloned) {
            $clonedField = $field->replicate();
            $clonedField->template_id = $cloned->id;
            $clonedField->save();
        });
    }


}

?>
