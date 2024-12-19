<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('referenced_table', 128)->nullable();
            $table->string('referenced_id', 128)->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('filename', 128);
            $table->string('description', 1024);

            $table->string('file')->nullable()->comment('{"subtype" : "file", "file_types" : ["txt", "pdf", "jpg"]}');

            $table->timestamp('created_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            $table->timestamp('updated_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('attachments');
    }
};
