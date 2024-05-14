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
        Schema::create('user_conf.profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('profile_type_id');
            $table->string('description');
            $table->string('unique_name')->unique();
            $table->boolean('active')->default(true);
            $table->uuid('creator_id')->nullable();
            $table->uuid('updater_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('profile_type_id', 'ProfilesProfileTypeIdFk')
                ->references('id')
                ->on('user_conf.profile_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('users.profiles', ['profile_type_id']);
        Schema::dropIfExists('user_conf.profiles');
    }
};
