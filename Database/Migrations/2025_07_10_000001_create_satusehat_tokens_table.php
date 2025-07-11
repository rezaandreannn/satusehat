<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatusehatTokensTable extends Migration
{
    public function up()
    {
        Schema::create('satusehat_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_type')->default('Bearer');
            $table->text('access_token');
            $table->integer('expires_in');
            $table->timestamp('expires_at');
            $table->string('environment')->default('sandbox');
            $table->timestamps();

            $table->index(['environment', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('satusehat_tokens');
    }
}
