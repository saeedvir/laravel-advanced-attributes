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
        Schema::create(config('laravel-advanced-attributes.tables.attributables', 'attributables'), function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('attribute_id'); // BIGINT UNSIGNED NOT NULL
            $table->string('attributable_type', 255); // VARCHAR(255) NOT NULL
            $table->unsignedBigInteger('attributable_id'); // BIGINT UNSIGNED NOT NULL
            $table->integer('value_int')->nullable(); // INT NULL DEFAULT NULL
            $table->decimal('value_decimal', 10, 2)->nullable(); // DECIMAL(10,2) NULL DEFAULT NULL
            $table->text('value_text')->nullable(); // TEXT NULL DEFAULT NULL
            $table->date('value_date')->nullable(); // DATE NULL DEFAULT NULL
            $table->boolean('value_boolean')->nullable(); // TINYINT(1) NULL DEFAULT NULL
            $table->json('value_json')->nullable(); // JSON NULL DEFAULT NULL
            $table->timestamps(); // TIMESTAMP NULL DEFAULT NULL for `created_at` and `updated_at`

            // Indexes
            $table->index(['attributable_type', 'attributable_id'], 'attributables_attributable_type_attributable_id_index'); // Composite index
            $table->fullText('value_text'); // FULLTEXT index on `value_text`

            // Foreign key constraint
            $table->foreign('attribute_id')
                ->references('id')
                ->on('attributes')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-advanced-attributes.tables.attributables', 'attributables'));
    }
};
