<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxClass;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaxClassController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = TaxClass::select('tax_classes.*')
                ->orderByDesc('is_default')
                ->orderBy('name');

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }
            if ($request->filled('default_filter')) {
                $query->where('is_default', $request->default_filter === '1');
            }

            return DataTables::of($query)
                ->addColumn('name_html', function (TaxClass $t) {
                    return '<span class="tax-name">'.e($t->name).'</span>';
                })
                ->addColumn('rate_html', function (TaxClass $t) {
                    $rate = rtrim(rtrim(number_format((float) $t->rate, 4), '0'), '.');
                    return '<span class="rate-badge">'.$rate.'<span class="rate-pct">%</span></span>';
                })
                ->addColumn('default_html', function (TaxClass $t) {
                    return $t->is_default
                        ? '<span class="b-default"><i class="bi bi-check-circle-fill"></i> Default</span>'
                        : '<span class="b-off"><i class="bi bi-dash-circle"></i> No</span>';
                })
                ->addColumn('status_html', function (TaxClass $t) {
                    return $t->is_active
                        ? '<span class="b-active"><i class="bi bi-circle-fill"></i> Active</span>'
                        : '<span class="b-inactive"><i class="bi bi-circle-fill"></i> Inactive</span>';
                })
                ->addColumn('actions', function (TaxClass $t) {
                    $edit = route('admin.tax-classes.edit', $t);
                    $del  = route('admin.tax-classes.destroy', $t);
                    return '
                        <div class="act-wrap">
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this tax class?\')">
                            '.csrf_field().''.method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['name_html','rate_html','default_html','status_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'      => TaxClass::count(),
            'active'     => TaxClass::where('is_active', true)->count(),
            'is_default' => TaxClass::where('is_default', true)->count(),
            'avg_rate'   => round((float) (TaxClass::avg('rate') ?? 0), 2),
        ];

        return view('admin.tax-classes.index', compact('stats'));
    }

    public function create()
    {
        return view('admin.tax-classes.form', ['taxClass' => new TaxClass()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->applyDefault($data);
        TaxClass::create($data);
        return redirect()->route('admin.tax-classes.index')->with('success', 'Tax class created.');
    }

    public function edit(TaxClass $taxClass)
    {
        return view('admin.tax-classes.form', compact('taxClass'));
    }

    public function update(Request $request, TaxClass $taxClass)
    {
        $data = $this->validateData($request);
        $this->applyDefault($data, $taxClass->id);
        $taxClass->update($data);
        return redirect()->route('admin.tax-classes.index')->with('success', 'Tax class updated.');
    }

    public function destroy(TaxClass $taxClass)
    {
        $taxClass->delete();
        return back()->with('success', 'Tax class deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'rate'       => ['required', 'numeric', 'min:0', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
            'is_active'  => ['nullable', 'boolean'],
        ]) + [
            'is_default' => $request->boolean('is_default'),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function applyDefault(array &$data, ?int $exceptId = null): void
    {
        if (! empty($data['is_default'])) {
            TaxClass::when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->update(['is_default' => false]);
        }
    }
}
