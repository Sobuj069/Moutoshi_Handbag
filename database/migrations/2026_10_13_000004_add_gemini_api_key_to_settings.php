<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $defaultKey = env('GEMINI_API_KEY', '');
        if ($defaultKey) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'gemini_api_key'],
                [
                    'value' => $defaultKey,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'gemini_api_key')->delete();
    }
};
