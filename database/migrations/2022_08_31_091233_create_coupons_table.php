<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {

            $table->id('coupon_id');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('type_id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('subtype_id');
            $table->foreign('subtype_id')->references('subtype_id')->on('subtypes')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('limit')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('value')->nullable();
            $table->string('email')->nullable();
            $table->string('code');
            $table->integer('used_times')->default(0);
            $table->string('status')->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
