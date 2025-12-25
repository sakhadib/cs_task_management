<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meeting_attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_log_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('meeting_log_id')->references('id')->on('meeting_logs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['meeting_log_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_attendees', function (Blueprint $table) {
            if (Schema::hasColumn('meeting_attendees', 'meeting_log_id')) {
                $table->dropForeign(['meeting_log_id']);
            }
            if (Schema::hasColumn('meeting_attendees', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });
        Schema::dropIfExists('meeting_attendees');
    }
};
