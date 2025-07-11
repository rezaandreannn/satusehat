<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatusehatLogsTable extends Migration
{
    public function up()
    {
        Schema::create('satusehat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('endpoint');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('status')->default('success'); // success, failed, error
            $table->text('error_message')->nullable();
            $table->decimal('execution_time', 8, 3)->nullable(); // dalam detik
            $table->string('environment')->default('sandbox');
            $table->timestamps();

            $table->index(['method', 'endpoint', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('satusehat_logs');
    }
}
