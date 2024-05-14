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
        DB::unprepared("CREATE SCHEMA IF NOT EXISTS user_conf");

        Schema::create('user_conf.users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('person_id')->nullable();
            $table->string('name');
            $table->string('short_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->uuid('creator_id')->nullable();
            $table->uuid('updater_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('person_id', 'UsersPersonIdFk')
                ->references('id')
                ->on('person.persons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('users.users', ['person_id']);
        Schema::dropIfExists('users.users');
    }
};
