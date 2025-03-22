<?php

use App\Models\RedeemCode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('redeem_code_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RedeemCode::class);
            $table->string('item_name');
            $table->text('item_description');
            $table->integer('item_quantity');
            $table->string('item_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeem_code_details');
    }
};
