<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PromoBannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = PromoBanner::query();

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }
            if ($request->filled('placement')) {
                $query->where('placement', $request->placement);
            }

            $query->orderBy('sort_order')->orderBy('id');

            return DataTables::of($query)
                ->addColumn('preview_html', function (PromoBanner $b) {
                    return '<div class="banner-swatch" style="background:'.$b->bgGradient().';">'
                         . '<span>'.e($b->title).'</span></div>';
                })
                ->addColumn('content_html', function (PromoBanner $b) {
                    $eyebrow   = $b->eyebrow ? '<div class="slide-eyebrow">'.e($b->eyebrow).'</div>' : '';
                    $sub       = $b->subtitle ? '<div class="slide-sub">'.e($b->subtitle).'</div>' : '';
                    $btn       = $b->button_text
                        ? '<div class="slide-btn-preview">'.e($b->button_text).' → '.e($b->button_url ?? '#').'</div>'
                        : '';
                    $placement = $b->placement === 'bottom'
                        ? '<span class="placement-badge placement-bottom"><i class="bi bi-arrow-down-circle-fill"></i> Bottom</span>'
                        : '<span class="placement-badge placement-top"><i class="bi bi-arrow-up-circle-fill"></i> Top</span>';
                    return $placement.$eyebrow.'<div class="slide-title-txt">'.e($b->title).'</div>'.$sub.$btn;
                })
                ->addColumn('image_html', function (PromoBanner $b) {
                    $url = $b->imageUrl();
                    return $url
                        ? '<img src="'.$url.'" class="slide-thumb" alt="" style="width:56px;height:42px;object-fit:cover;border-radius:8px;">'
                        : '<span class="b-off">No image</span>';
                })
                ->addColumn('order_html', function (PromoBanner $b) {
                    return '<span class="order-badge">'.$b->sort_order.'</span>';
                })
                ->addColumn('status_html', function (PromoBanner $b) {
                    return $b->is_active
                        ? '<span class="b-approved"><i class="bi bi-check-circle-fill"></i> Active</span>'
                        : '<span class="b-off"><i class="bi bi-dash-circle-fill"></i> Hidden</span>';
                })
                ->addColumn('actions', function (PromoBanner $b) {
                    $edit = route('admin.promo-banners.edit', $b);
                    $del  = route('admin.promo-banners.destroy', $b);
                    return '
                        <div class="act-wrap">
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this banner?\')">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['preview_html','content_html','image_html','order_html','status_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'  => PromoBanner::count(),
            'active' => PromoBanner::where('is_active', true)->count(),
        ];

        return view('admin.promo-banners.index', compact('stats'));
    }

    public function create()
    {
        return view('admin.promo-banners.form', ['banner' => new PromoBanner([
            'button_style'   => 'white',
            'bg_color_start' => '#3d1c02',
            'bg_color_end'   => '#6b3a1f',
            'text_color'     => 'light',
            'placement'      => 'top',
            'is_active'      => true,
            'sort_order'     => PromoBanner::max('sort_order') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        $data   = $this->validateData($request);
        $banner = PromoBanner::create($data);
        $this->syncImage($request, $banner);
        return redirect()->route('admin.promo-banners.index')->with('success', 'Promo banner created.');
    }

    public function edit(PromoBanner $promoBanner)
    {
        return view('admin.promo-banners.form', ['banner' => $promoBanner]);
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $data = $this->validateData($request);
        $promoBanner->update($data);
        $this->syncImage($request, $promoBanner);
        return redirect()->route('admin.promo-banners.index')->with('success', 'Promo banner updated.');
    }

    public function destroy(PromoBanner $promoBanner)
    {
        if ($promoBanner->image_path) {
            Storage::disk('public')->delete($promoBanner->image_path);
        }
        $promoBanner->delete();
        return back()->with('success', 'Promo banner deleted.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'eyebrow'        => ['nullable', 'string', 'max:80'],
            'title'          => ['required', 'string', 'max:120'],
            'subtitle'       => ['nullable', 'string', 'max:255'],
            'button_text'    => ['nullable', 'string', 'max:60'],
            'button_url'     => ['nullable', 'string', 'max:255'],
            'button_style'   => ['required', 'in:dark,white,outline'],
            'bg_color_start' => ['required', 'string', 'max:20'],
            'bg_color_end'   => ['required', 'string', 'max:20'],
            'text_color'     => ['required', 'in:dark,light'],
            'sort_order'     => ['required', 'integer', 'min:0'],
            'placement'      => ['required', 'in:top,bottom'],
            'image'          => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        return $validated;
    }

    private function syncImage(Request $request, PromoBanner $banner): void
    {
        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('promo-banners', 'public');
            $banner->update(['image_path' => $path]);
        }

        if ($request->boolean('delete_image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->update(['image_path' => null]);
        }
    }
}
