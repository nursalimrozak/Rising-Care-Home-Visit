<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_type', ['dp', 'full', 'addon'])->default('full')->after('booking_id');
            $table->decimal('required_amount', 10, 2)->default(0)->after('total_amount');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'required_amount']);
        });
    }
};
