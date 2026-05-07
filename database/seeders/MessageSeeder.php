<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@rently.com')->first();
        $tenant = User::where('email', 'tenant@rently.com')->first();

        $conversation = Conversation::create([
            'tenant_id' => $tenant->id,
            'property_manager_id' => $manager->id,
            'last_message_at' => now(),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $tenant->id,
            'body' => 'Hi John, just wanted to check if the boiler service has been scheduled yet?',
            'is_system_message' => false,
            'read_at' => now(),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $manager->id,
            'body' => 'Hi Jane, yes it has been booked for the 15th. Someone will be there between 9am and 12pm.',
            'is_system_message' => false,
            'read_at' => null,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $manager->id,
            'body' => 'Your tenancy agreement is ready to review and sign.',
            'is_system_message' => true,
            'read_at' => null,
        ]);
    }
}
