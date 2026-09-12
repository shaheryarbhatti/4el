<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = Brand::withCount('products')
                ->select('brands.*')
                ->orderBy('sort_order')
                ->orderBy('name');

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }
            if ($request->filled('featured')) {
                $query->where('is_featured', $request->featured === '1');
            }

            return DataTables::of($query)
                ->addColumn('logo_html', function (Brand $b) {
                    if ($b->logo) {
                        return '<img src="'.asset('storage/'.$b->logo).'" class="brand-thumb" alt="">';
                    }
                    $initial = mb_strtoupper(mb_substr($b->name, 0, 1));
                    $hue     = abs(crc32($b->name)) % 360;
                    return '<div class="brand-avatar" style="--av-hue:'.$hue.'">'.$initial.'</div>';
                })
                ->addColumn('name_html', function (Brand $b) {
                    $link = $b->url
                        ? '<br><a href="'.e($b->url).'" target="_blank" class="brand-url"><i class="bi bi-link-45deg"></i> '.e($b->url).'</a>'
                        : '';
                    return '<span class="brand-name">'.e($b->name).'</span>'
                         . '<br><small class="brand-slug">'.e($b->slug).'</small>'
                         . $link;
                })
                ->addColumn('featured_html', function (Brand $b) {
                    return $b->is_featured
                        ? '<span class="b-feat"><i class="bi bi-star-fill"></i> Featured</span>'
                        : '<span class="b-off"><i class="bi bi-star"></i> No</span>';
                })
                ->addColumn('status_html', function (Brand $b) {
                    return $b->is_active
                        ? '<span class="b-active"><i class="bi bi-circle-fill"></i> Active</span>'
                        : '<span class="b-inactive"><i class="bi bi-circle-fill"></i> Inactive</span>';
                })
                ->addColumn('products_html', function (Brand $b) {
                    return '<span class="prod-count">'.$b->products_count.'</span>';
                })
                ->addColumn('actions', function (Brand $b) {
                    $edit = route('admin.brands.edit', $b);
                    $del  = route('admin.brands.destroy', $b);
                    return '
                        <div class="act-wrap">
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this brand?\')">
                            '.csrf_field().''.method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['logo_html','name_html','featured_html','status_html','products_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'     => Brand::count(),
            'active'    => Brand::where('is_active', true)->count(),
            'featured'  => Brand::where('is_featured', true)->count(),
            'with_logo' => Brand::whereNotNull('logo')->count(),
        ];

        return view('admin.brands.index', compact('stats'));
    }

    public function create()
    {
        return view('admin.brands.form', ['brand' => new Brand()]);
    }

    public function store(Request $request)
    {
        $data         = $this->validateData($request);
        $data['logo'] = $this->handleLogo($request, null);
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data         = $this->validateData($request);
        $data['logo'] = $this->handleLogo($request, $brand->logo);
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back()->with('success', 'Brand deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'url'         => ['nullable', 'url', 'max:255'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer'],
        ]) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active'),
            'sort_order'  => (int) $request->input('sort_order', 0),
        ];
    }

    private function handleLogo(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('logo')) {
            return $request->file('logo')->store('brands', 'public');
        }
        return $current;
    }
}
