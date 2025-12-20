<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $messages = $query->latest()->paginate(15);
        
        return view('admin.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contact)
    {
        $contact->update(['status' => 'read']);
        return view('admin.contacts.show', compact('contact'));
    }

    public function respond(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'admin_response' => 'required|string'
        ]);

        $contact->update([
            'admin_response' => $request->admin_response,
            'status' => 'responded'
        ]);

        return back()->with('success', 'Response sent successfully!');
    }
}