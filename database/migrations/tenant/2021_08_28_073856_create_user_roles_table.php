<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the user_roles table (many to many relationship)
 * @author frederic
 *
 */
class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');            
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            
            /*
             * ALTER TABLE `tenantabbeville`.`user_roles` ADD UNIQUE (`user_id`, `role_id`); 
             * 
             * For some reason that I do not understand the creation of an unique index on the two foreign keys
             * delete one of the two foreign key.
             * However everything seams to work as expected with two foreign keys including cascade
             */
            $table->unique(['user_id', 'role_id'], 'unique_combination');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
