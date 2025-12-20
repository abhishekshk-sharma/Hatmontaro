<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutUs;
use App\Models\TeamMember;

class AboutUsSeeder extends Seeder
{
    public function run()
    {
        // Create About Us sections
        AboutUs::create([
            'section' => 'hero',
            'title' => 'About Aksharam Fashion',
            'content' => 'Revolutionizing fashion retail with AI-powered personalization and cutting-edge technology. We\'re not just selling clothes - we\'re crafting your perfect style story.',
            'extra_data' => json_encode([
                'stats' => [
                    ['label' => 'Happy Customers', 'value' => '10K+'],
                    ['label' => 'Products', 'value' => '5K+'],
                    ['label' => 'AI Accuracy', 'value' => '99%']
                ]
            ]),
            'sort_order' => 1
        ]);

        AboutUs::create([
            'section' => 'mission',
            'title' => 'Our Mission',
            'content' => 'To revolutionize the fashion industry by combining artificial intelligence with human creativity, making personalized style accessible to everyone while promoting sustainable and ethical fashion practices.',
            'sort_order' => 2
        ]);

        AboutUs::create([
            'section' => 'vision',
            'title' => 'Our Vision',
            'content' => 'To become the world\'s leading AI-driven fashion platform, where technology and style converge to create unique, personalized experiences that inspire confidence and self-expression in every individual.',
            'sort_order' => 3
        ]);

        // Create default team members
        TeamMember::create([
            'name' => 'Arjun Sharma',
            'position' => 'Chief Executive Officer',
            'description' => 'Visionary leader with 15+ years in fashion tech. Former VP at major fashion retailers, passionate about democratizing style through AI.',
            'image' => null,
            'linkedin_url' => '#',
            'twitter_url' => '#',
            'email' => 'arjun@aksharamfashion.com',
            'color' => '#7c3aed',
            'icon' => 'bi-crown',
            'sort_order' => 1
        ]);

        TeamMember::create([
            'name' => 'Priya Patel',
            'position' => 'Chief Technology Officer',
            'description' => 'AI/ML expert with PhD in Computer Vision. Former Google AI researcher, specializing in fashion recommendation systems and computer vision.',
            'image' => null,
            'linkedin_url' => '#',
            'email' => 'priya@aksharamfashion.com',
            'color' => '#28a745',
            'icon' => 'bi-cpu',
            'sort_order' => 2
        ]);
    }
}