<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project.projects_tags', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('project_id');
            $table->uuid('tag_id');
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('project_id', 'ProjectsTagsProjectIdFk')
                ->references('id')
                ->on('project.projects');

            $table
                ->foreign('tag_id', 'ProjectsTagsTagIdFk')
                ->references('id')
                ->on('general.tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('project.projects_tags', ['project_id', 'tag_id']);
        Schema::dropIfExists('projects_tags');
    }
};
