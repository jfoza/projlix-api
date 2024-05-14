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
        DB::unprepared("CREATE SCHEMA IF NOT EXISTS person");

        Schema::connection('pgsql')->create('person.persons', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('city_id')->nullable();
            $table->string('phone', 15)->unique();
            $table->string('zip_code', 8)->nullable();
            $table->string('address')->nullable();
            $table->string('number_address')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('uf', 2)->nullable();
            $table->boolean('active')->default(true);
            $table->uuid('creator_id')->nullable();
            $table->uuid('updater_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('now()'));
            $table->timestamp('updated_at')->default(DB::raw('now()'));

            $table
                ->foreign('city_id', 'PersonsCityIdFk')
                ->references('id')
                ->on('city.cities');

            $table->index('city_id');
            $table->index('uf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('person.persons', ['city_id']);
        Schema::dropIfExists('person.persons');
    }
};
