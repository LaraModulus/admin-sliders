<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('viewable')->default(false)->index();
            $table->dateTime('from_date')->useCurrent()->index();
            $table->dateTime('to_date')->nullable()->index();
            $table->smallInteger('pos')->default(0)->index();
            foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
                $table->string('title_'.$locale)->nullable();
                $table->string('sub_title_'.$locale)->nullable();
                $table->text('description_'.$locale)->nullable();
                $table->string('link_'.$locale)->nullable();
                $table->string('image_'.$locale)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sliders');
    }
}
