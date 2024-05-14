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
        Schema::create('project.cards', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('code');
            $table->uuid('section_id');
            $table->uuid('user_id');
            $table->uuid('tag_project_id');
            $table->string('description');
            $table->string('status');
            $table->date('limit_date')->nullable();
            $table->uuid('creator_id')->nullable();
            $table->uuid('updater_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('section_id', 'CardsSectionIdFk')
                ->references('id')
                ->on('project.sections');

            $table
                ->foreign('user_id', 'CardsUserIdFk')
                ->references('id')
                ->on('user_conf.users');

            $table
                ->foreign('tag_project_id', 'CardsTagProjectIdFk')
                ->references('id')
                ->on('project.projects_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('project.cards', ['section_id', 'tag_project_id']);
        Schema::dropIfExists('project.cards');
    }
};
