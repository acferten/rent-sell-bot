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
        Schema::table('estates', function (Blueprint $table) {
            $table->foreign('user_id', 'estates_user_id_foreign')->references('id')->on('users');
            $table->foreign('geoposition_id', 'estates_geoposition_id_foreign')->references('id')->on('geopositions');
            $table->foreign('estate_type_id', 'estates_estate_type_id_foreign')->references('id')->on('estate_types');
            $table->foreign('deal_type_id', 'estates_deal_type_id_foreign')->references('id')->on('deal_types');
            $table->foreign('status_id', 'estates_status_id_foreign')->references('id')->on('estate_statuses');
        });

        Schema::table('estate_includes', function (Blueprint $table) {
            $table->foreign('estate_id', 'estate_includes_estate_id_foreign')->references('id')->on('estates');
            $table->foreign('include_id', 'estate_includes_include_id_foreign')->references('id')->on('includes');
        });

        Schema::table('estate_photos', function (Blueprint $table) {
            $table->foreign('estate_id', 'estate_photos_estate_id_foreign')->references('id')->on('estates');
        });

        Schema::table('estate_prices', function (Blueprint $table) {
            $table->foreign('estate_id', 'estate_prices_estate_id_foreign')->references('id')->on('estates');
            $table->foreign('period_id', 'estate_prices_period_id_foreign')->references('id')->on('periods');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('estate_id', 'reports_estate_id_foreign')->references('id')->on('estates');
            $table->foreign('reason_id', 'reports_reason_id_foreign')->references('id')->on('report_reasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->dropForeign('estates_user_id_foreign');
            $table->dropForeign('estates_geoposition_id_foreign');
            $table->dropForeign('estates_estate_type_id_foreign');
            $table->dropForeign('estates_deal_type_id_foreign');
            $table->dropForeign('estates_status_id_foreign');
        });

        Schema::table('estate_includes', function (Blueprint $table) {
            $table->dropForeign('estate_includes_estate_id_foreign');
            $table->dropForeign('estate_includes_include_id_foreign');
        });

        Schema::table('estate_photos', function (Blueprint $table) {
            $table->dropForeign('estate_photos_estate_id_foreign');
        });

        Schema::table('estate_prices', function (Blueprint $table) {
            $table->dropForeign('estate_prices_estate_id_foreign');
            $table->dropForeign('estate_prices_period_id_foreign');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_estate_id_foreign');
            $table->dropForeign('reports_reason_id_foreign');
        });
    }
};
