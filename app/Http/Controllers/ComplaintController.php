<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = auth()->user()->complaints()->with('order')->latest()->paginate(10);
        return view('user.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('user.complaints.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        auth()->user()->complaints()->create($request->all());

        return redirect()->route('user.complaints.index')->with('success', 'Complaint submitted successfully. We will review it shortly.');
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.complaints.show', compact('complaint'));
    }
}