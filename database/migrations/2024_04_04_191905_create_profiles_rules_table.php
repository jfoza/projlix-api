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
        Schema::create('user_conf.profiles_rules', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('profile_id');
            $table->uuid('rule_id');
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('profile_id', 'ProfilesRulesProfileIdFk')
                ->references('id')
                ->on('user_conf.profiles')
                ->onDelete('cascade');

            $table->foreign('rule_id', 'ProfilesRulesRuleIdFk')
                ->references('id')
                ->on('user_conf.rules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('user_conf.profiles_rules', ['profile_id', 'rule_id']);
        Schema::dropIfExists('user_conf.profiles_rules');
    }
};
