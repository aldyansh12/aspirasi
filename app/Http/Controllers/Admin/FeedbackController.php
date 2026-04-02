<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\AspirasiRespondedMail;

class FeedbackController extends Controller
{
    public function index()
    {
        $query = aspirasi::with('user', 'kategori');

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('feedback_title', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('username', 'like', "%{$search}%")
                           ->orWhere('full_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $feedbacks = $query->latest()->paginate(10)->withQueryString();

        return view('admin.feedback', [
            'feedbacks' => $feedbacks
        ]);
    }

    public function show($id)
    {
        $feedback = aspirasi::with(['user', 'kategori'])->findOrFail($id);
        return view('admin.feedback-detail', compact('feedback'));
    }

    public function reply(Request $request, $id)
    {
        $aspirasi = aspirasi::with('user', 'kategori')->findOrFail($id);
        
        $request->validate([
            'admin_response' => 'required|string'
        ]);

        $aspirasi->update([
            'admin_response' => $request->admin_response,
            'admin_id' => Auth::id(),
            'status' => 'complete'
        ]);

        // Send email notification to student
        if ($aspirasi->user && $aspirasi->user->email) {
            try {
                Mail::to($aspirasi->user->email)->send(new AspirasiRespondedMail($aspirasi));
            } catch (\Exception $e) {
                \Log::error('Failed to send response email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, $id)
    {
        $aspirasi = aspirasi::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:complete,on_progress'
        ]);

        // Check if the current status is complete and trying to change to on_progress
        if ($aspirasi->status === 'complete' && $request->status === 'on_progress') {
            return back()->with('error', 'Aspirasi yang sudah complete tidak dapat dikembalikan ke on progress.');
        }

        $aspirasi->update([
            'status' => $request->status
        ]);

        // Send email notification to student if status completed
        if ($request->status === 'complete' && $aspirasi->user && $aspirasi->user->email) {
            try {
                Mail::to($aspirasi->user->email)->send(new AspirasiRespondedMail($aspirasi));
            } catch (\Exception $e) {
                \Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status updated successfully');
    }
}
