<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanToTenantRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('tenant_requests', function (Blueprint $table) {
            $table->string('plan')->default('free'); // Set 'free' as the default value
        });
    }

    public function down()
    {
        Schema::table('tenant_requests', function (Blueprint $table) {
            $table->dropColumn('plan'); // Drop the plan column if the migration is rolled back
        });
    }
}
