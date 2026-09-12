<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySpecification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategorySpecificationController extends Controller
{
    /** Overview: all categories with spec counts — landing page + DataTables AJAX endpoint. */
    public function overview(Request $request)
    {
        if ($request->has('draw')) {
            $query = Category::withCount('specifications')
                ->with(['children' => fn($q) => $q->withCount('specifications')])
                ->whereNull('parent_id')
                ->select('categories.*');

            return DataTables::of($query)
                ->addColumn('image_html', function (Category $cat) {
                    $colors = [
                        ['#6c63ff','#a78bfa'], ['#10b981','#34d399'], ['#f59e0b','#fbbf24'],
                        ['#3b82f6','#60a5fa'], ['#ec4899','#f9a8d4'], ['#8b5cf6','#c4b5fd'],
                        ['#14b8a6','#5eead4'], ['#f97316','#fdba74'], ['#06b6d4','#67e8f9'],
                        ['#84cc16','#bef264'],
                    ];
                    $idx      = abs(crc32($cat->name)) % count($colors);
                    [$c1, $c2] = $colors[$idx];
                    $parts    = preg_split('/\s+/', trim($cat->name));
                    $initials = mb_strtoupper(mb_substr($parts[0], 0, 1));
                    if (count($parts) > 1) {
                        $initials .= mb_strtoupper(mb_substr(end($parts), 0, 1));
                    }
                    $avatarStyle = 'width:44px;height:44px;border-radius:12px;display:inline-flex;'
                        .'align-items:center;justify-content:center;color:#fff;font-size:14px;'
                        .'font-weight:800;flex-shrink:0;background:linear-gradient(135deg,'.$c1.','.$c2.');';
                    $avatar = '<div style="'.$avatarStyle.'">'.$initials.'</div>';

                    if ($url = $cat->image_url) {
                        return '<img src="'.$url.'" alt="" '
                            .'style="width:44px;height:44px;border-radius:12px;object-fit:cover;border:2px solid #e2e8f0;">';
                    }
                    return $avatar;
                })
                ->addColumn('name_html', function (Category $cat) {
                    $sub = $cat->children->count();
                    return '<span class="cat-title">'.$cat->name.'</span>'
                         . ($sub ? '<br><small class="cat-sub-count">'.$sub.' sub-categor'.($sub===1?'y':'ies').'</small>' : '');
                })
                ->addColumn('specs_html', function (Category $cat) {
                    $n = $cat->specifications_count;
                    if ($n > 0) {
                        return '<span class="spec-count has"><i class="bi bi-check-circle-fill"></i> '.$n.' spec'.($n!==1?'s':'').'</span>';
                    }
                    return '<span class="spec-count none"><i class="bi bi-exclamation-circle"></i> None yet</span>';
                })
                ->addColumn('sub_html', function (Category $cat) {
                    if ($cat->children->isEmpty()) {
                        return '<span style="color:#cbd5e1;font-size:12px;">—</span>';
                    }
                    $pills = '';
                    foreach ($cat->children->sortBy('name') as $child) {
                        $href = route('admin.category-specifications.index', $child);
                        $cls  = $child->specifications_count > 0 ? 'has-specs' : 'no-specs';
                        $sup  = $child->specifications_count > 0
                            ? '<sup class="pill-badge">'.$child->specifications_count.'</sup>' : '';
                        $pills .= '<a href="'.$href.'" class="sub-pill '.$cls.'">'.$child->name.$sup.'</a> ';
                    }
                    return '<div class="sub-pills">'.$pills.'</div>';
                })
                ->addColumn('actions', function (Category $cat) {
                    $href = route('admin.category-specifications.index', $cat);
                    $has  = $cat->specifications_count > 0;
                    $cls  = $has ? 'btn-specs edit' : 'btn-specs add';
                    $icon = $has ? 'bi-pencil-fill' : 'bi-plus-lg';
                    $lbl  = $has ? 'Manage Specs'  : 'Add Specs';
                    return '<a href="'.$href.'" class="'.$cls.'"><i class="bi '.$icon.'"></i> '.$lbl.'</a>';
                })
                ->rawColumns(['image_html','name_html','specs_html','sub_html','actions'])
                ->make(true);
        }

        $totalSpecs     = CategorySpecification::count();
        $totalCats      = Category::whereNull('parent_id')->count();
        $catsWithSpecs  = Category::whereNull('parent_id')->whereHas('specifications')->count();
        $subTotal       = Category::whereNotNull('parent_id')->count();
        $subWithSpecs   = Category::whereNotNull('parent_id')->whereHas('specifications')->count();

        return view('admin.category-specifications.overview',
            compact('totalSpecs','totalCats','catsWithSpecs','subTotal','subWithSpecs'));
    }

    public function index(Category $category)
    {
        $specs = $category->specifications()->orderBy('sort_order')->get();
        return view('admin.category-specifications.index', compact('category', 'specs'));
    }

    public function create(Category $category)
    {
        $spec = new CategorySpecification(['is_active' => true, 'is_essential' => true, 'sort_order' => 0]);
        return view('admin.category-specifications.form', compact('category', 'spec'));
    }

    public function store(Request $request, Category $category)
    {
        $data = $this->validateSpec($request);
        $data['category_id'] = $category->id;
        CategorySpecification::create($data);
        return redirect()->route('admin.category-specifications.index', $category)
            ->with('success', 'Specification added.');
    }

    public function edit(Category $category, CategorySpecification $specification)
    {
        $spec = $specification;
        return view('admin.category-specifications.form', compact('category', 'spec'));
    }

    public function update(Request $request, Category $category, CategorySpecification $specification)
    {
        $specification->update($this->validateSpec($request, $specification));
        return redirect()->route('admin.category-specifications.index', $category)
            ->with('success', 'Specification updated.');
    }

    public function destroy(Category $category, CategorySpecification $specification)
    {
        $specification->delete();
        return back()->with('success', 'Specification deleted.');
    }

    // AJAX reorder via drag-and-drop
    public function reorder(Request $request, Category $category)
    {
        foreach ($request->input('order', []) as $idx => $id) {
            CategorySpecification::where('id', $id)
                ->where('category_id', $category->id)
                ->update(['sort_order' => $idx]);
        }
        return response()->json(['ok' => true]);
    }

    // API: get all active specs for a category (used by the listing form AJAX)
    public function apiSpecs(Category $category)
    {
        $specs = $category->specifications()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn($s) => [
                'id'           => $s->id,
                'name'         => $s->name,
                'field_label'  => $s->display_label,
                'field_type'   => $s->field_type,
                'options'      => $s->option_list,
                'placeholder'  => $s->placeholder,
                'unit'         => $s->unit,
                'is_required'  => $s->is_required,
                'is_essential' => $s->is_essential,
            ]);

        return response()->json($specs);
    }

    private function validateSpec(Request $request, ?CategorySpecification $existing = null): array
    {
        // Determine the category id from the route
        $categoryId = request()->route('category')?->id;

        // Unique name per category (ignore self on update)
        $uniqueRule = \Illuminate\Validation\Rule::unique('category_specifications', 'name')
            ->where('category_id', $categoryId);
        if ($existing) {
            $uniqueRule->ignore($existing->id);
        }

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150', 'regex:/^[a-z0-9_]+$/', $uniqueRule],
            'field_label'  => 'required|string|max:150',
            'field_type'   => 'required|in:text,textarea,select,multiselect,toggle,range,number',
            'options'      => 'nullable|string',
            'placeholder'  => 'nullable|string|max:200',
            'unit'         => 'nullable|string|max:30',
            'is_required'  => 'boolean',
            'is_essential' => 'boolean',
            'is_active'    => 'boolean',
            'sort_order'   => 'integer|min:0',
        ], [
            'name.regex'  => 'Internal name may only contain lowercase letters, numbers and underscores.',
            'name.unique' => 'This internal name is already used in this category. Please choose a different Display Label.',
        ]);

        // Parse options: one per line → JSON array
        if (!empty($data['options'])) {
            $opts = array_filter(array_map('trim', explode("\n", $data['options'])));
            $data['options'] = array_values($opts);
        } else {
            $data['options'] = null;
        }

        $data['is_required']  = $request->boolean('is_required');
        $data['is_essential'] = $request->boolean('is_essential');
        $data['is_active']    = $request->boolean('is_active');

        return $data;
    }
}
