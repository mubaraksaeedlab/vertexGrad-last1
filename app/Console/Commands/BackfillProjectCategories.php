<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\ProjectCategory;

class BackfillProjectCategories extends Command
{
    protected $signature = 'projects:backfill-categories';
    protected $description = 'Backfill project_category_id from old category text';

    public function handle(): int
    {
        $map = [
            'information technology' => 'information-technology',
            'software engineering' => 'software-engineering',
            'artificial intelligence & machine learning' => 'artificial-intelligence-machine-learning',
            'medical / health' => 'medical-health',
            'electrical engineering' => 'electrical-engineering',
            'renewable energy' => 'renewable-energy',
            'agriculture' => 'agriculture',
            'education' => 'education',
            'business / management' => 'business-management',
            'other' => 'other',
        ];

        $projects = Project::query()
            ->whereNull('project_category_id')
            ->whereNotNull('category')
            ->get();

        $updated = 0;

        foreach ($projects as $project) {
            $normalized = mb_strtolower(trim((string) $project->category));
            $slug = $map[$normalized] ?? 'other';

            $category = ProjectCategory::where('slug', $slug)->first();

            if ($category) {
                $project->update([
                    'project_category_id' => $category->id,
                ]);

                $updated++;
            }
        }

        $this->info("Updated {$updated} projects.");

        return self::SUCCESS;
    }
}