<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPanelIdToTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Add nullable FK to panels so existing teams won't break
            $table->unsignedBigInteger('panel_id')->nullable()->after('id')->index();
            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['panel_id']);
            $table->dropIndex(['panel_id']);
            $table->dropColumn('panel_id');
        });
    }
}
