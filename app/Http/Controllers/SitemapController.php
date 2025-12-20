<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function generate()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/products'))
            ->add(Url::create('/contact'))
            ->add(Url::create('/products'))
            ->add(Url::create('/products/ai-recommended'))
            ->add(Url::create('/men'))
            ->add(Url::create('/women'))
            ->add(Url::create('/children'))
            ->add(Url::create('/caps'))
            ->add(Url::create('/caps/tryon'))
            ->add(Url::create('/api/caps/recommendations'))
            ->add(Url::create('/ai/recommend'))
            ->add(Url::create('/cart'))
            ->add(Url::create('/my-cart'))
            ->add(Url::create('/profile'))
            ->add(Url::create('/orders'))
            ->add(Url::create('/complaints'))
            ->add(Url::create('/wishlist'))
            ->add(Url::create('/privacy'))
            ->add(Url::create('/terms'));

        // Save to public/sitemap.xml
        $sitemap->writeToFile(public_path('sitemap.xml'));

        return response()->json(['status' => 'ok', 'message' => 'Sitemap generated']);
    }
}
