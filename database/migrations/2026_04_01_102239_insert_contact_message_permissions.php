<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            [
                'name' => 'View Contact Messages',
                'slug' => 'view_contact_messages',
                'group' => 'messages',
                'description' => 'Allows the user to view contact messages submitted from the frontend.',
            ],
            [
                'name' => 'Reply Contact Messages',
                'slug' => 'reply_contact_messages',
                'group' => 'messages',
                'description' => 'Allows the user to send replies to contact messages.',
            ],
            [
                'name' => 'Update Contact Message Status',
                'slug' => 'update_contact_message_status',
                'group' => 'messages',
                'description' => 'Allows the user to update contact message statuses.',
            ],
        ];

        foreach ($permissions as $permission) {
            $exists = DB::table('permissions')->where('slug', $permission['slug'])->exists();

            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'group' => $permission['group'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('slug', [
            'view_contact_messages',
            'reply_contact_messages',
            'update_contact_message_status',
        ])->delete();
    }
};