<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {

    $table->id();

    $table->string('agent_code')->unique();

    $table->string('signature_code', 10)
        ->nullable()
        ->unique();

    $table->string('username')
        ->unique();

    $table->string('otp')->nullable();

    $table->decimal('agent_balance', 15, 2)
        ->default(0);

    $table->string('email')
        ->nullable()
        ->unique();

    $table->string('password')
        ->nullable();

    $table->string('ip')->nullable();
    $table->string('last_ip')->nullable();

    $table->text('user_agent')->nullable();
    $table->text('last_user_agent')->nullable();

     $table->string('device_fingerprint', 255)->nullable();
     $table->string('last_device_fingerprint', 255)->nullable();

     $table->decimal('latitude', 10, 7)->nullable();
     $table->decimal('longitude', 10, 7)->nullable();
     $table->decimal('accuracy', 10, 2)->nullable();

     $table->timestamp('device_verified_at')->nullable();
    
    $table->string('phone')
        ->nullable();

    $table->enum('role', [
        'SUPERADMIN',
        'ADMIN'
    ])->default('ADMIN');

    $table->enum('status', [
        'active',
        'inactive',
        'suspended'
    ])->default('active');

    $table->timestamp('last_login_at')
        ->nullable();

    $table->rememberToken();

    $table->timestamps();

    $table->index('status');
    $table->index('role');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};