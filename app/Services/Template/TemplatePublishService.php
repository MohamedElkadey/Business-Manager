<?php
use Illuminate\Support\Facades\DB;
use App\Models\Template;
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