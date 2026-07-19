<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::latest();

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('subject', 'like', "%$q%")
                   ->orWhere('message', 'like', "%$q%");
            });
        }

        if ($request->status) {
            if ($request->status === 'pending') {
                $query->whereNull('replied_at');
            } elseif ($request->status === 'replied') {
                $query->whereNotNull('replied_at');
            }
        }

        $contacts = $query->paginate(15)->withQueryString();

        $stats = [
            'total'   => Contact::count(),
            'pending' => Contact::whereNull('replied_at')->count(),
            'replied' => Contact::whereNotNull('replied_at')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $body = $request->content;
            $subject = $request->subject;

            Mail::raw($body, function ($message) use ($contact, $subject) {
                $message->to($contact->email)
                        ->subject($subject);
            });

            $contact->update([
                'reply_subject' => $subject,
                'reply_content' => $body,
                'replied_at'    => now(),
            ]);

            return back()->with('success', 'Gửi email phản hồi cho ' . $contact->name . ' thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gửi email thất bại: ' . $e->getMessage());
        }
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa tin nhắn liên hệ!');
    }
}
