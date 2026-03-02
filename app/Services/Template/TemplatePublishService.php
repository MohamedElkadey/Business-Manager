<?php
use Illuminate\Support\Facades\DB;
use App\Models\Template;
use App\Models\Company;
class TemplatePublishService
{
    public function publish(Template $template): Template
    {
        if ($template->status !== 'draft') {
            throw new DomainException('Only draft templates can be published.');
        }

        return DB::transaction(function () use ($template) {

            $locked = Template::where('id', $template->id)
                ->where('status', 'draft')
                ->lockForUpdate()
                ->firstOrFail();

            $this->validateTemplateStructure($locked);

            $locked->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            return $locked->fresh();
        });
    }
    public function checkPublishable(Template $template): bool
    {
        try {
            $this->validateTemplateStructure($template);
            return true;
        } catch (DomainException $e) {
            return false;
        }
    }
    public function publishedTemplates(Company $company){
        return $company->templates()->where('status', 'published')->get();
    }
    public function getPublishedTemplateById(Company $company, int $templateId){
        return $company->templates()->where('status', 'published')->findOrFail($templateId);
    }
    public function is_published(Template $template): bool{
        return $template->status === 'published';
    }

    public function is_draft(Template $template): bool{
        return $template->status === 'draft';
    }
    public function sh_published(Template $template){
        if($this->is_published($template))
            throw new DomainException('Template must be published.');
    }
    private function validateTemplateStructure(Template $template): void
    {
        if ($template->field()->count() === 0) {
            throw new DomainException('Template must contain at least one field.');
        }

        if (empty($template->expression)) {
            throw new DomainException('Pricing expression is required.');
        }

        // Later:
        // Validate pricing inputs match expression variables
    }

    public function archive(Template $template){
        if($template->status !== 'published'){
            throw new DomainException('Only published templates can be archived.');
        }
        $template->update([
            'status' => 'archived',
        ]);
    }
}




?>