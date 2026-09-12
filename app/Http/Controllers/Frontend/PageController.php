<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\VendorProfile;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function contact()
    {
        return view('frontend.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        // No DB storage / email sending for now – just flash success
        return redirect()->route('contact')
            ->with('contact_success', 'Thank you, ' . $request->name . '! Your message has been received. We will get back to you within 24 hours.');
    }

    public function about()
    {
        $usersCount   = User::count();
        $productsCount = Product::where('status', 'approved')->count();
        $vendorsCount  = VendorProfile::where('status', 'approved')->count();

        return view('frontend.about', compact('usersCount', 'productsCount', 'vendorsCount'));
    }
}
