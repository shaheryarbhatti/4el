<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\TaxClass;
use Illuminate\Http\Request;

/**
 * Vendor shop settings (frontend): store profile + selling defaults.
 */
class ShopController extends Controller
{
    public function edit()
    {
        $profile    = auth()->user()->vendorProfile;
        $taxClasses = TaxClass::where('is_active', true)->orderBy('name')->get();

        return view('vendor.shop', compact('profile', 'taxClasses'));
    }

    public function update(Request $request)
    {
        $profile = auth()->user()->vendorProfile;

        $data = $request->validate([
            'store_name'            => ['required', 'string', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:40'],
            'address'               => ['nullable', 'string', 'max:500'],
            'description'           => ['nullable', 'string', 'max:1000'],
            'logo'                  => ['nullable', 'image', 'max:2048'],
            'default_shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'default_tax_class_id'  => ['nullable', 'exists:tax_classes,id'],
        ]);

        $data['default_free_shipping'] = $request->boolean('default_free_shipping');
        $data['default_shipping_cost'] = $data['default_shipping_cost'] ?? 0;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Shop settings saved.');
    }
}
