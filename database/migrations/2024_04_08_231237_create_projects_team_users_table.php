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
        Schema::create('user_conf.projects_team_users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('team_user_id');
            $table->uuid('project_id');
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('team_user_id', 'ProjectsTeamUsersTeamUserIdFk')
                ->references('id')
                ->on('user_conf.team_users');
            $table
                ->foreign('project_id', 'ProjectsTeamUsersProjectIdIdFk')
                ->references('id')
                ->on('project.projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('user_conf.projects_team_users', ['team_user_id', 'project_id']);
        Schema::dropIfExists('user_conf.projects_team_users');
    }
};
