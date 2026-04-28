<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    /**
     * Create project and attach files/media (works for Admin one-page + Student 4-step).
     *
     * @param  array  $data   validated project fields
     * @param  array|null $adminFiles  admin uploaded files array (project_files)
     * @param  array|null $tempMediaPaths ['photo' => 'tmp/..', 'video' => 'tmp/..']
     */
    public function create(array $data, ?array $adminFiles = null, ?array $tempMediaPaths = null): Project
    {
        $project = Project::create($data);

        // 1) Admin uploads (project_files table relation)
        if ($adminFiles) {
            foreach ($adminFiles as $file) {
                if (!$file) continue;

                $path = $file->store('projects/' . ($project->id ?? $project->project_id), 'public');

                $mime = $file->getMimeType();
                $type = str_contains($mime, 'image') ? 'image'
                    : (str_contains($mime, 'video') ? 'video'
                    : (str_contains($mime, 'pdf') ? 'pdf' : 'other'));

                // If you have relation: $project->files()
                if (method_exists($project, 'files')) {
                    $project->files()->create([
                        'file_path' => $path,
                        'file_type' => $type,
                    ]);
                }
            }
        }

        // 2) Student wizard uploads (Spatie Media temp files)
        // Only if your Project model supports addMedia (Spatie)
        if ($tempMediaPaths && method_exists($project, 'addMedia')) {
            if (!empty($tempMediaPaths['photo'])) {
                $project->addMedia(storage_path('app/public/' . $tempMediaPaths['photo']))
                        ->toMediaCollection('images');
            }

            if (!empty($tempMediaPaths['video'])) {
                $project->addMedia(storage_path('app/public/' . $tempMediaPaths['video']))
                        ->toMediaCollection('videos');
            }

            // optional: delete temp files after moving to media
            foreach (['photo','video'] as $k) {
                if (!empty($tempMediaPaths[$k])) {
                    Storage::disk('public')->delete($tempMediaPaths[$k]);
                }
            }
        }

        return $project;
    }
}