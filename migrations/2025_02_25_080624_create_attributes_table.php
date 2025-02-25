<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-advanced-attributes.tables.attributes', 'attributes'), function (Blueprint $table) {
            $table->id(); // This will create a BIGINT UNSIGNED NOT NULL AUTO_INCREMENT column named 'id'
            $table->string('name', 255)->collation('utf8mb4_unicode_ci'); // VARCHAR(255) NOT NULL
            $table->enum('type', ['int', 'decimal', 'text', 'date', 'boolean', 'json'])->collation('utf8mb4_unicode_ci'); // ENUM NOT NULL
            $table->string('unit', 255)->nullable()->collation('utf8mb4_unicode_ci'); // VARCHAR(255) NULL DEFAULT NULL
            $table->unsignedTinyInteger('is_required')->default(0); // TINYINT UNSIGNED NOT NULL DEFAULT '0'
            $table->string('default_value', 255)->nullable()->collation('utf8mb4_unicode_ci'); // VARCHAR(255) NULL DEFAULT NULL
            $table->text('description')->nullable()->collation('utf8mb4_unicode_ci'); // TEXT NULL DEFAULT NULL
            $table->timestamps(); // This will create 'created_at' and 'updated_at' TIMESTAMP columns

            // Indexes
            $table->index('name'); // INDEX on 'name'
            $table->index('type'); // INDEX on 'type'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-advanced-attributes.tables.attributes', 'attributes'));
    }
};
