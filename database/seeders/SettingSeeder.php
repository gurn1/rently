<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'key'   => 'site_name',
                'value' => 'Rently',
                'type'  => 'string',
                'group' => 'general',
                'label' => 'Site Name',
                'hint'  => 'The name of your portal displayed in the browser tab and emails.',
            ],
            [
                'key'   => 'contact_email',
                'value' => 'hello@rently.com',
                'type'  => 'string',
                'group' => 'general',
                'label' => 'Contact Email',
                'hint'  => 'Main contact email address displayed to users.',
            ],
            [
                'key'   => 'support_phone',
                'value' => '',
                'type'  => 'string',
                'group' => 'general',
                'label' => 'Support Phone Number',
                'hint'  => 'Optional phone number displayed on the portal.',
            ],

            // Payments
            [
                'key'   => 'stripe_key',
                'value' => '',
                'type'  => 'string',
                'group' => 'payments',
                'label' => 'Stripe Publishable Key',
                'hint'  => 'Your Stripe publishable key (pk_live_... or pk_test_...).',
            ],
            [
                'key'   => 'stripe_secret',
                'value' => '',
                'type'  => 'encrypted',
                'group' => 'payments',
                'label' => 'Stripe Secret Key',
                'hint'  => 'Your Stripe secret key. Stored encrypted.',
            ],
            [
                'key'   => 'stripe_webhook_secret',
                'value' => '',
                'type'  => 'encrypted',
                'group' => 'payments',
                'label' => 'Stripe Webhook Secret',
                'hint'  => 'Your Stripe webhook signing secret (whsec_...).',
            ],
            [
                'key'   => 'default_payment_method',
                'value' => 'stripe',
                'type'  => 'string',
                'group' => 'payments',
                'label' => 'Default Payment Method',
                'hint'  => 'Default method used when generating lease payments (stripe or manual).',
            ],
            [
                'key'   => 'rent_due_day',
                'value' => '1',
                'type'  => 'integer',
                'group' => 'payments',
                'label' => 'Rent Due Day',
                'hint'  => 'Day of the month rent is due (1-28).',
            ],

            // Leases
            [
                'key'   => 'default_lease_length',
                'value' => '12',
                'type'  => 'integer',
                'group' => 'leases',
                'label' => 'Default Lease Length (months)',
                'hint'  => 'Default number of months used when creating a new lease.',
            ],
            [
                'key'   => 'auto_generate_payments',
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'leases',
                'label' => 'Auto-generate Payments',
                'hint'  => 'Automatically create monthly payment records when a lease becomes active.',
            ],
            [
                'key'   => 'payment_reminder_days',
                'value' => '3',
                'type'  => 'integer',
                'group' => 'leases',
                'label' => 'Payment Reminder Days',
                'hint'  => 'How many days before the due date to send a payment reminder.',
            ],

            // Notifications
            [
                'key'   => 'email_notifications_enabled',
                'value' => '0',
                'type'  => 'boolean',
                'group' => 'notifications',
                'label' => 'Enable Email Notifications',
                'hint'  => 'Send email notifications in addition to in-app notifications.',
            ],
            [
                'key'   => 'admin_notification_email',
                'value' => '',
                'type'  => 'string',
                'group' => 'notifications',
                'label' => 'Admin Notification Email',
                'hint'  => 'Email address that receives admin-level notifications.',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}