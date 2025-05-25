<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('password');
            $table->boolean('is_admin')->default(false)->after('phone');
            $table->string('avatar')->nullable()->after('is_admin');
            $table->text('address')->nullable()->after('avatar');
            $table->boolean('status')->default(true)->after('address');
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'is_admin', 'avatar', 'address', 'status', 'last_login_at'
            ]);
        });
    }
};
