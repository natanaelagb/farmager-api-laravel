<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('specie')->nullable();
            $table->string('breed')->nullable();
            $table->string('description')->nullable();
            $table->decimal('weight', 10, 3)->nullable()->unsigned();
            $table->date('birth_date')->nullable();
            $table->text('vaccines')->nullable();
            $table->string('gender')->nullable();
            $table->timestamps();
            $table->date('death_at')->nullable();
            $table->softDeletes();
        });
        Schema::table('animals', function (Blueprint $table) {
            $table->foreignIdFor(App\Models\Animal::class, 'father_id')->nullable()->constrained('animals')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(App\Models\Animal::class, 'mother_id')->nullable()->constrained('animals')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
};
