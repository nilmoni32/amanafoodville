<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * @var Array
     */
    protected $settings =[
        [
            'key'   => 'site_name',
            'value' => 'FunVille Restaurant',
        ],
        [
            'key'   => 'site_title',
            'value' => 'FunVille E-commerce',
        ],
        [
            'key'   => 'default_email_address',
            'value' => 'mustafi.amana@gmail.com',
        ],
        [
            'key'   => 'currency_code',
            'value' => 'BDT',
        ],
        [
            'key'   => 'currency_symbol',
            'value' => 'Tk',
        ],
        [
            'key'   => 'site_logo',
            'value' => '',
        ],
        [
            'key'   => 'site_favicon',
            'value' => '',
        ],
        [
            'key'   => 'footer_copyright_text',
            'value' => '',
        ],
        [
            'key'   => 'seo_meta_title',
            'value' => '',
        ],
        [
            'key'   => 'seo_meta_description',
            'value' => '',
        ],
        [
            'key'   => 'social_facbook',
            'value' => '',
        ],
        [
            'key'   => 'social_twitter',
            'value' => '',
        ],
        [
            'key'   => 'social_instagram',
            'value' => '',
        ],
        [
            'key'   => 'social_linkedin',
            'value' => '',
        ],
        [
            'key'   => 'facebook_pixels',
            'value' => '',
        ],
        [
            'key'   => 'google_analytics',
            'value' => '',
        ],
        [
            'key'   => 'paypal_payment_method',
            'value' => '',
        ],
        [
            'key'   => 'paypal_client_id',
            'value' => '',
        ],
        [
            'key'   => 'paypal_secret_id',
            'value' => '',
        ],
        [
            'key'   => 'phone_no',
            'value' => '+88017111111',
        ],
        [
            'key'   => 'contact_address',
            'value' => 'Funville, Rajshahi',
        ],
        [
            'key'   => 'social_youtube',
            'value' => '',
        ],
        [
            'key'   => 'delivery_charge',
            'value' => '',
        ],
        [
            'key'   => 'client_lists',
            'value' => '',
        ],
        [
            'key'   => 'open_hours',
            'value' => '',
        ],
        [
            'key'   => 'google_map',
            'value' => '',
        ],
        [
            'key'   => 'tax_percentage',
            'value' => '',
        ],
        [
            'key'   => 'email_recipient',
            'value' => '',
        ],
        [
            'key'   => 'scheduler_timings',
            'value' => '',
        ],
        [
            'key'   => 'money_to_point',
            'value' => '',
        ],
        [
            'key'   => 'point_to_money',
            'value' => '',
        ],
        [
            'key'   => 'total_tbls',
            'value' => '',
        ],
        [
            'key'   => 'ref_email_recipient',
            'value' => '',
        ],
	    [
            'key'   => 'due_booking_amount',
            'value' => '',
        ],
        [
            'key'   => 'hide_order_amount',
            'value' => '',
        ],
        [
            'key'   => 'tax_include_percentage',
            'value' => '',
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->settings as $index => $setting){
            // creating database seeds using Setting model and create() takes an $setting array argument.
            $result = Setting::create($setting);
            if(!$result){
                $this->command->info("Insert failed at record $index"); // used for console output.
            }
        }

        $this->command->info("Total ".count($this->settings)." records has been inserted"); // $this->settings (array).
    }
}
