<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $sections = \App\Models\AboutUs::where('is_active', true)->orderBy('sort_order')->get();
        $teamMembers = \App\Models\TeamMember::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.about', compact('sections', 'teamMembers'));
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        \App\Models\ContactMessage::create($request->all());
        
        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}