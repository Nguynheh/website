<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('chuongtrinh_id')->nullable()->after('ugroup_id'); 
            $table->foreign('chuongtrinh_id')
                ->references('id')->on('chuong_trinh_dao_tao')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['chuongtrinh_id']);
            $table->dropColumn('chuongtrinh_id');
        });
    }
};

