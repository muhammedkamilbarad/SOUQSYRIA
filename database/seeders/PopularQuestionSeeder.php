<?php

namespace Database\Seeders;

use App\Models\PopularQuestion;
use Illuminate\Database\Seeder;

class PopularQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $popularQuestions = [
            [
                'question' => 'How do I update my payment method?',
                'answer' => 'You can update your payment method by going to Account Settings > Payment Methods > Add New Method. Follow the prompts to enter your new payment information.',
                'category' => 'Payment',
                'priority' => 'High',
                'status' => true,
            ],
            [
                'question' => 'How can I cancel my subscription?',
                'answer' => 'To cancel your subscription, navigate to Account Settings > Subscriptions > Current Plan and click "Cancel Subscription". Please note that you\'ll continue to have access until the end of your billing period.',
                'category' => 'Subscribtion',
                'priority' => 'High',
                'status' => true,
            ],
            [
                'question' => 'What advertising formats do you support?',
                'answer' => 'We support various advertising formats including banner ads, video ads, native ads, and sponsored content. Each format has specific requirements detailed in our advertising guidelines.',
                'category' => 'Advertisement',
                'priority' => 'Medium',
                'status' => true,
            ],
            [
                'question' => 'How do I reset my password?',
                'answer' => 'Click on the "Forgot Password" link on the login page. Enter your email address, and we\'ll send you instructions to reset your password. The reset link expires in 24 hours.',
                'category' => 'System',
                'priority' => 'High',
                'status' => true,
            ],
            [
                'question' => 'What are the system requirements?',
                'answer' => 'Our platform works best with modern browsers like Chrome, Firefox, Safari, or Edge. We recommend having a stable internet connection and at least 2GB of RAM for optimal performance.',
                'category' => 'System',
                'priority' => 'Medium',
                'status' => true,
            ],
            [
                'question' => 'How do I contact customer support?',
                'answer' => 'You can reach our customer support team through the Help Center, by emailing support@example.com, or by using the live chat feature during business hours (9 AM - 5 PM EST).',
                'category' => 'General',
                'priority' => 'Medium',
                'status' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. Cryptocurrency payments are currently in beta testing.',
                'category' => 'Payment',
                'priority' => 'Medium',
                'status' => true,
            ],
            [
                'question' => 'How do I report a technical issue?',
                'answer' => 'To report a technical issue, go to Help Center > Report an Issue and fill out the form. Include as much detail as possible, including screenshots if applicable.',
                'category' => 'System',
                'priority' => 'Low',
                'status' => true,
            ],
            [
                'question' => 'What is your refund policy?',
                'answer' => 'Refund requests must be submitted within 30 days of purchase. Each case is reviewed individually. Approved refunds are processed within 5-7 business days.',
                'category' => 'Payment',
                'priority' => 'High',
                'status' => true,
            ],
            [
                'question' => 'How do I upgrade my subscription?',
                'answer' => 'To upgrade your subscription, go to Account Settings > Subscriptions > Upgrade Plan. Choose your desired plan and follow the payment instructions.',
                'category' => 'Subscribtion',
                'priority' => 'Medium',
                'status' => false,
            ],
        ];

        foreach ($popularQuestions as $question) {
            PopularQuestion::create($question);
        }
    }
}