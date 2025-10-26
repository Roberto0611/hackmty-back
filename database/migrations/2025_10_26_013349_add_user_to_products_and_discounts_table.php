<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Esto hace 3 cosas:
            // 1. Crea la columna `user_id` como `unsignedBigInteger`
            // 2. La hace `nullable`
            // 3. Añade la 'foreign key constraint' (la relación) a la tabla 'users'
            // 4. (Opcional) Si el usuario se borra, pone 'user_id' a NULL
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained() // Esto crea la relación con la tabla 'users'
                  ->onDelete('set null'); // Opcional, pero recomendado si es nullable
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
};
