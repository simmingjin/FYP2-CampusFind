
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add an admin flag to existing users.
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        // Track whether a found item is available for claiming.
        Schema::table('found_items', function (Blueprint $table) {
            $table->string('status')
                ->default('available');
        });

        // Add claim information to the existing claims table.
        Schema::table('claims', function (Blueprint $table) {
            $table->foreignId('found_item_id')
                ->constrained('found_items')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('claim_reason');

            // Ownership proof is optional.
            $table->string('proof_image')->nullable();

            $table->string('status')->default('pending');

            $table->text('admin_remark')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['found_item_id']);

            $table->dropColumn([
                'found_item_id',
                'user_id',
                'claim_reason',
                'proof_image',
                'status',
                'admin_remark',
                'reviewed_by',
                'reviewed_at',
            ]);
        });

        Schema::table('found_items', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
