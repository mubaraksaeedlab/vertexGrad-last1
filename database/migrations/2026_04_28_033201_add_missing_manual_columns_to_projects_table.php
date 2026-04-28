<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'project_type')) {
                $table->string('project_type')->nullable();
            }

            if (!Schema::hasColumn('projects', 'project_nature')) {
                $table->string('project_nature')->nullable();
            }

            if (!Schema::hasColumn('projects', 'supervisor_status')) {
                $table->string('supervisor_status', 50)->nullable();
            }

            if (!Schema::hasColumn('projects', 'supervisor_decision')) {
                $table->enum('supervisor_decision', [
                    'pending',
                    'approved',
                    'revision_requested',
                    'rejected',
                ])->nullable()->default('pending');
            }

            if (!Schema::hasColumn('projects', 'scan_score')) {
                $table->decimal('scan_score', 5, 2)->nullable();
            }

            if (!Schema::hasColumn('projects', 'problem_statement')) {
                $table->text('problem_statement')->nullable();
            }

            if (!Schema::hasColumn('projects', 'target_beneficiaries')) {
                $table->text('target_beneficiaries')->nullable();
            }

            if (!Schema::hasColumn('projects', 'student_name')) {
                $table->string('student_name')->nullable();
            }

            if (!Schema::hasColumn('projects', 'academic_level')) {
                $table->string('academic_level', 100)->nullable();
            }

            if (!Schema::hasColumn('projects', 'supervisor_name')) {
                $table->string('supervisor_name')->nullable();
            }

            if (!Schema::hasColumn('projects', 'supervisor_title')) {
                $table->string('supervisor_title')->nullable();
            }

            if (!Schema::hasColumn('projects', 'university_name')) {
                $table->string('university_name')->nullable();
            }

            if (!Schema::hasColumn('projects', 'college_name')) {
                $table->string('college_name')->nullable();
            }

            if (!Schema::hasColumn('projects', 'department')) {
                $table->string('department')->nullable();
            }

            if (!Schema::hasColumn('projects', 'governorate')) {
                $table->string('governorate', 100)->nullable();
            }

            if (!Schema::hasColumn('projects', 'is_feasible')) {
                $table->string('is_feasible', 20)->nullable();
            }

            if (!Schema::hasColumn('projects', 'local_implementation')) {
                $table->string('local_implementation', 20)->nullable();
            }

            if (!Schema::hasColumn('projects', 'expected_impact')) {
                $table->text('expected_impact')->nullable();
            }

            if (!Schema::hasColumn('projects', 'community_benefit')) {
                $table->text('community_benefit')->nullable();
            }

            if (!Schema::hasColumn('projects', 'needs_funding')) {
                $table->string('needs_funding', 20)->nullable();
            }

            if (!Schema::hasColumn('projects', 'duration_months')) {
                $table->integer('duration_months')->nullable();
            }

            if (!Schema::hasColumn('projects', 'support_type')) {
                $table->string('support_type', 100)->nullable();
            }

            if (!Schema::hasColumn('projects', 'budget_breakdown')) {
                $table->text('budget_breakdown')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_1')) {
                $table->text('milestone_1')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_1_month')) {
                $table->integer('milestone_1_month')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_2')) {
                $table->text('milestone_2')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_2_month')) {
                $table->integer('milestone_2_month')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_3')) {
                $table->text('milestone_3')->nullable();
            }

            if (!Schema::hasColumn('projects', 'milestone_3_month')) {
                $table->integer('milestone_3_month')->nullable();
            }

            if (!Schema::hasColumn('projects', 'scanner_status')) {
                $table->string('scanner_status', 50)->nullable();
            }

            if (!Schema::hasColumn('projects', 'scan_report')) {
                $table->longText('scan_report')->nullable();
            }

            if (!Schema::hasColumn('projects', 'scanned_at')) {
                $table->timestamp('scanned_at')->nullable();
            }

            if (!Schema::hasColumn('projects', 'scanner_project_id')) {
                $table->string('scanner_project_id')->nullable();
            }

            if (!Schema::hasColumn('projects', 'final_decision')) {
                $table->string('final_decision', 50)->nullable();
            }

            if (!Schema::hasColumn('projects', 'final_notes')) {
                $table->text('final_notes')->nullable();
            }

            if (!Schema::hasColumn('projects', 'final_decided_at')) {
                $table->timestamp('final_decided_at')->nullable();
            }

            if (!Schema::hasColumn('projects', 'final_decided_by')) {
                $table->unsignedBigInteger('final_decided_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $columns = [
                'project_type',
                'project_nature',
                'supervisor_status',
                'supervisor_decision',
                'scan_score',
                'problem_statement',
                'target_beneficiaries',
                'student_name',
                'academic_level',
                'supervisor_name',
                'supervisor_title',
                'university_name',
                'college_name',
                'department',
                'governorate',
                'is_feasible',
                'local_implementation',
                'expected_impact',
                'community_benefit',
                'needs_funding',
                'duration_months',
                'support_type',
                'budget_breakdown',
                'milestone_1',
                'milestone_1_month',
                'milestone_2',
                'milestone_2_month',
                'milestone_3',
                'milestone_3_month',
                'scanner_status',
                'scan_report',
                'scanned_at',
                'scanner_project_id',
                'final_decision',
                'final_notes',
                'final_decided_at',
                'final_decided_by',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};