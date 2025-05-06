<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTenantsTableDropSubdomainColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Check if the 'subdomain' column exists before trying to drop it
            if (Schema::hasColumn('tenants', 'subdomain')) {
                $table->dropColumn('subdomain');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // You can optionally add back the 'subdomain' column if needed
            $table->string('subdomain')->nullable();
        });
    }
}
