<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddUuidToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['users', 'roles', 'offices', 'divisions'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (!Schema::hasColumn($table, 'uuid')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->uuid('uuid')->nullable()->after('id')->unique();
                    });
                }

                // Populate existing records
                $records = DB::table($table)->get();
                foreach ($records as $record) {
                    DB::table($table)->where('id', $record->id)->update([
                        'uuid' => (string) Str::uuid()
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['users', 'roles', 'offices', 'divisions'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }
        }
    }
}
