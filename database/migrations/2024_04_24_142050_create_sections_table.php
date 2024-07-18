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
        Schema::create('project.sections', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('project_id');
            $table->uuid('color_id');
            $table->uuid('icon_id');
            $table->string('name');
            $table->bigInteger('section_order');
            $table->uuid('creator_id')->nullable();
            $table->uuid('updater_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('project_id', 'SectionProjectIdFk')
                ->references('id')
                ->on('project.projects')
                ->onDelete('cascade');

            $table
                ->foreign('color_id', 'SectionColorIdFk')
                ->references('id')
                ->on('general.colors');

            $table
                ->foreign('icon_id', 'SectionIconIdFk')
                ->references('id')
                ->on('general.icons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('project.sections', ['project_id', 'color_id', 'icon_id']);
        Schema::dropIfExists('project.sections');
    }
};
