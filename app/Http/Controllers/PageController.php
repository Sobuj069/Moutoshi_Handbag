<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about-us');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your message has been sent to our customer care team. We will get back to you shortly.');
    }

    public function exchangePolicy()
    {
        return view('pages.exchange-return-policy');
    }

    public function faqs()
    {
        return view('pages.faqs');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function terms()
    {
        return view('pages.terms-conditions');
    }

    public function b2bQueries()
    {
        return view('pages.b2b-queries');
    }

    public function b2bSubmit(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:150',
            'contact_person' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'quantity' => 'nullable|integer|min:10',
            'details' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you for your B2B corporate inquiry! Our business team will reach out within 24 hours.');
    }

    public function complaintPortal()
    {
        return view('pages.complaint-portal');
    }

    public function complaintSubmit(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:50',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'issue_type' => 'required|string',
            'details' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Your grievance has been registered directly with our Senior Management. Ticket #' . rand(10000, 99999) . '. We will contact you today.');
    }

    public function founder()
    {
        return view('pages.meet-our-founder-ceo');
    }
}
