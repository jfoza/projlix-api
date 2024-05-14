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
        Schema::create('user_conf.users_cards', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('user_id');
            $table->uuid('card_id');
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('user_id', 'UsersCardsUserIdFk')
                ->references('id')
                ->on('user_conf.users')
                ->onDelete('cascade');

            $table
                ->foreign('card_id', 'UsersCardsCardIdFk')
                ->references('id')
                ->on('project.cards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('user_conf.users_cards', ['user_id', 'card_id']);
        Schema::dropIfExists('user_conf.users_cards');
    }
};