<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $sections = AboutUs::orderBy('sort_order')->get();
        $teamMembers = TeamMember::orderBy('sort_order')->get();
        return view('admin.about.index', compact('sections', 'teamMembers'));
    }

    public function editSection($id)
    {
        $section = AboutUs::findOrFail($id);
        return view('admin.about.edit-section', compact('section'));
    }

    public function updateSection(Request $request, $id)
    {
        $section = AboutUs::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'extra_data' => 'nullable|string'
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        if (isset($data['extra_data'])) {
            $data['extra_data'] = json_decode($data['extra_data'], true);
        }

        $section->update($data);
        return redirect()->route('admin.about.index')->with('success', 'Section updated successfully');
    }

    public function teamIndex()
    {
        $teamMembers = TeamMember::orderBy('sort_order')->get();
        return view('admin.about.team-index', compact('teamMembers'));
    }

    public function teamCreate()
    {
        return view('admin.about.team-create');
    }

    public function teamStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'email' => 'nullable|email',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'sort_order' => 'integer'
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('team', 'public');
        }

        TeamMember::create($data);
        return redirect()->route('admin.about.team.index')->with('success', 'Team member added successfully');
    }

    public function teamEdit($id)
    {
        $member = TeamMember::findOrFail($id);
        return view('admin.about.team-edit', compact('member'));
    }

    public function teamUpdate(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'email' => 'nullable|email',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'sort_order' => 'integer'
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $data['image'] = $request->file('image')->store('team', 'public');
        }

        $member->update($data);
        return redirect()->route('admin.about.team.index')->with('success', 'Team member updated successfully');
    }

    public function teamDestroy($id)
    {
        $member = TeamMember::findOrFail($id);
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }
        $member->delete();
        return redirect()->route('admin.about.team.index')->with('success', 'Team member deleted successfully');
    }
}
