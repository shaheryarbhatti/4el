<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin CRUD for product categories (and sub-categories via parent_id).
 */
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = Category::with('parent')
                ->select('categories.*')
                ->orderByRaw('COALESCE(parent_id, id)')   // group children under their parent
                ->orderByRaw('parent_id IS NOT NULL')      // parent row first (NULL = 0 sorts before 1)
                ->orderBy('sort_order');

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }
            if ($request->filled('featured')) {
                $query->where('is_featured', $request->featured === '1');
            }
            if ($request->filled('on_home')) {
                $query->where('show_on_home', $request->on_home === '1');
            }
            if ($request->filled('parent_filter')) {
                if ($request->parent_filter === 'root') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $request->parent_filter);
                }
            }

            return DataTables::of($query)
                ->addColumn('image_html', function ($cat) {
                    if ($cat->image_url) {
                        return '<img src="'.$cat->image_url.'" class="cat-thumb" alt="">';
                    }
                    // Map FA icon names to Bootstrap Icons equivalents
                    $biMap = [
                        'fa-laptop'   => 'bi-laptop',      'fa-car'      => 'bi-car-front',
                        'fa-gem'      => 'bi-gem',          'fa-home'     => 'bi-house',
                        'fa-tshirt'   => 'bi-bag',          'fa-gamepad'  => 'bi-controller',
                        'fa-futbol'   => 'bi-trophy',       'fa-industry' => 'bi-building',
                        'fa-ring'     => 'bi-gem',          'fa-recycle'  => 'bi-arrow-repeat',
                        'fa-tag'      => 'bi-tag',
                    ];
                    $icon = $cat->icon ?? 'fa-tag';
                    $bi   = $biMap[$icon] ?? 'bi-tag';
                    return '<div class="cat-icon-ph"><i class="bi '.$bi.'"></i></div>';
                })
                ->addColumn('name_html', function ($cat) {
                    if ($cat->parent_id) {
                        $indent = '<span class="tree-indent"><span class="tree-line"></span></span>';
                    } else {
                        $indent = '';
                    }
                    return $indent.'<span class="cat-name'.($cat->parent_id ? '' : ' cat-parent-name').'">'.$cat->name.'</span>'
                         . '<br><small class="cat-slug">'.$cat->slug.'</small>';
                })
                ->addColumn('parent_html', function ($cat) {
                    return $cat->parent
                        ? '<span class="badge-parent">'.$cat->parent->name.'</span>'
                        : '<span class="root-badge"><i class="bi bi-diagram-3"></i> Root</span>';
                })
                ->addColumn('badges', function ($cat) {
                    $home = $cat->show_on_home
                        ? '<span class="b-on"><i class="bi bi-house-check"></i> On Home</span>'
                        : '<span class="b-off"><i class="bi bi-house-x"></i> Off</span>';
                    $feat = $cat->is_featured
                        ? '<span class="b-feat"><i class="bi bi-star-fill"></i> Featured</span>'
                        : '<span class="b-off">No</span>';
                    return $home.'<br>'.$feat;
                })
                ->addColumn('status_html', function ($cat) {
                    return $cat->is_active
                        ? '<span class="b-active"><i class="bi bi-circle-fill"></i> Active</span>'
                        : '<span class="b-inactive"><i class="bi bi-circle-fill"></i> Inactive</span>';
                })
                ->addColumn('actions', function ($cat) {
                    $edit  = route('admin.categories.edit', $cat);
                    $specs = route('admin.category-specifications.index', $cat);
                    $del   = route('admin.categories.destroy', $cat);
                    return '
                        <div class="act-wrap">
                          <a href="'.$specs.'" class="btn-act" style="background:#ede9fe;color:#6d28d9;" title="Item Specifications"><i class="bi bi-sliders"></i></a>
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this category?\')">
                            '.csrf_field().''.method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['image_html','name_html','parent_html','badges','status_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'    => Category::count(),
            'active'   => Category::where('is_active', true)->count(),
            'featured' => Category::where('is_featured', true)->count(),
            'on_home'  => Category::where('show_on_home', true)->count(),
        ];
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.index', compact('stats', 'parents'));
    }

    public function create()
    {
        $parents = Category::parents()->orderBy('name')->get();
        return view('admin.categories.form', ['category' => new Category(), 'parents' => $parents]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['image'] = $this->handleImage($request, $data['image'] ?? null);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        // Prevent choosing itself as its own parent.
        $parents = Category::parents()->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateData($request, $category->id);
        $data['image'] = $this->handleImage($request, $category->image);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    /* ---------------- helpers ---------------- */

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'parent_id'    => ['nullable', 'exists:categories,id'],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            'icon'         => ['nullable', 'string', 'max:100'],
            'is_featured'  => ['nullable', 'boolean'],
            'show_on_home' => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
            'sort_order'   => ['nullable', 'integer'],
        ]) + [
            // checkboxes: normalise to real booleans
            'is_featured'  => $request->boolean('is_featured'),
            'show_on_home' => $request->boolean('show_on_home'),
            'is_active'    => $request->boolean('is_active'),
            'sort_order'   => (int) $request->input('sort_order', 0),
        ];
    }

    // Store an uploaded image (public disk) or keep the existing one.
    private function handleImage(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('categories', 'public');
        }
        return $current;
    }
}
