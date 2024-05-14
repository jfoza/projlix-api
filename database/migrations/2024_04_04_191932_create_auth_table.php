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
        Schema::create('user_conf.auth', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->timestamp('initial_date')->nullable(false);
            $table->timestamp('final_date')->nullable();
            $table->text('token')->nullable(false);
            $table->ipAddress('ip_address')->nullable(false);
            $table->string('auth_type', 20)->nullable(false);
            $table->boolean('active')->default(true);
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('user_id', 'SessionsUserIdFk')
                ->references('id')
                ->on('user_conf.users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('user_conf.auth', ['user_id']);
        Schema::dropIfExists('user_conf.auth');
    }
};
