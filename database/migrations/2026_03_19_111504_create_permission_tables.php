<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('guard_name');
    $table->timestamps();
});

Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('guard_name');
    $table->timestamps();
});

Schema::create('model_has_roles', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
    $table->morphs('model');
    $table->primary(['role_id', 'model_id', 'model_type']);
});

Schema::create('model_has_permissions', function (Blueprint $table) {
    $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
    $table->morphs('model');
    $table->primary(['permission_id', 'model_id', 'model_type']);
});

Schema::create('role_has_permissions', function (Blueprint $table) {
    $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
    $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
    $table->primary(['permission_id', 'role_id']);
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
