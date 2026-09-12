<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HeroSlideController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = HeroSlide::query();

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            $query->orderBy('sort_order')->orderBy('id');

            return DataTables::of($query)
                ->addColumn('preview_html', function (HeroSlide $s) {
                    $bg = $s->bgGradient();
                    return '<div class="slide-swatch" style="background:'.$bg.';">'
                         . '<span class="slide-swatch-title">'.e($s->title).'</span>'
                         . '</div>';
                })
                ->addColumn('content_html', function (HeroSlide $s) {
                    $eyebrow = $s->eyebrow ? '<div class="slide-eyebrow">'.e($s->eyebrow).'</div>' : '';
                    $sub     = $s->subtitle ? '<div class="slide-sub">'.e($s->subtitle).'</div>' : '';
                    $btn     = $s->button_text
                        ? '<div class="slide-btn-preview">'.e($s->button_text).' &rarr; '.e($s->button_url ?? '#').'</div>'
                        : '';
                    return $eyebrow
                         . '<div class="slide-title-txt">'.e($s->title).'</div>'
                         . $sub . $btn;
                })
                ->addColumn('images_html', function (HeroSlide $s) {
                    $imgs = $s->imageItems();
                    if (empty($imgs)) {
                        return '<span class="b-off">No images</span>';
                    }
                    $out = '<div class="slide-thumbs">';
                    foreach ($imgs as $img) {
                        $out .= '<img src="'.asset('storage/'.$img['path']).'" alt="" class="slide-thumb" title="'.e($img['label']).'">';
                    }
                    return $out . '</div>';
                })
                ->addColumn('order_html', function (HeroSlide $s) {
                    return '<span class="order-badge">'.$s->sort_order.'</span>';
                })
                ->addColumn('status_html', function (HeroSlide $s) {
                    return $s->is_active
                        ? '<span class="b-approved"><i class="bi bi-check-circle-fill"></i> Active</span>'
                        : '<span class="b-off"><i class="bi bi-dash-circle-fill"></i> Hidden</span>';
                })
                ->addColumn('actions', function (HeroSlide $s) {
                    $edit = route('admin.hero-slides.edit', $s);
                    $del  = route('admin.hero-slides.destroy', $s);
                    return '
                        <div class="act-wrap">
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this slide?\')">
                            '.csrf_field().''.method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['preview_html','content_html','images_html','order_html','status_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'  => HeroSlide::count(),
            'active' => HeroSlide::where('is_active', true)->count(),
        ];

        return view('admin.hero-slides.index', compact('stats'));
    }

    public function create()
    {
        return view('admin.hero-slides.form', ['slide' => new HeroSlide([
            'button_style'    => 'dark',
            'bg_color_start'  => '#6c63ff',
            'bg_color_end'    => '#a78bfa',
            'text_color'      => 'light',
            'is_active'       => true,
            'sort_order'      => HeroSlide::max('sort_order') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        $data  = $this->validateData($request);
        $slide = HeroSlide::create($data);
        $this->syncImages($request, $slide);
        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide created.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.form', ['slide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $this->validateData($request);
        $heroSlide->update($data);
        $this->syncImages($request, $heroSlide);
        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide updated.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        foreach ([1, 2, 3] as $n) {
            $path = $heroSlide->{"img{$n}_path"};
            if ($path) Storage::disk('public')->delete($path);
        }
        $heroSlide->delete();
        return back()->with('success', 'Hero slide deleted.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:120'],
            'eyebrow'         => ['nullable', 'string', 'max:80'],
            'subtitle'        => ['nullable', 'string', 'max:200'],
            'button_text'     => ['nullable', 'string', 'max:60'],
            'button_url'      => ['nullable', 'string', 'max:255'],
            'button_style'    => ['required', 'in:dark,white'],
            'bg_color_start'  => ['required', 'string', 'max:20'],
            'bg_color_end'    => ['required', 'string', 'max:20'],
            'text_color'      => ['required', 'in:dark,light'],
            'img1_label'      => ['nullable', 'string', 'max:60'],
            'img1_url'        => ['nullable', 'string', 'max:255'],
            'img2_label'      => ['nullable', 'string', 'max:60'],
            'img2_url'        => ['nullable', 'string', 'max:255'],
            'img3_label'      => ['nullable', 'string', 'max:60'],
            'img3_url'        => ['nullable', 'string', 'max:255'],
            'img1'            => ['nullable', 'image', 'max:2048'],
            'img2'            => ['nullable', 'image', 'max:2048'],
            'img3'            => ['nullable', 'image', 'max:2048'],
            'sort_order'      => ['required', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function syncImages(Request $request, HeroSlide $slide): void
    {
        foreach ([1, 2, 3] as $n) {
            $field = "img{$n}";
            if ($request->hasFile($field)) {
                // Delete old image
                $old = $slide->{"img{$n}_path"};
                if ($old) Storage::disk('public')->delete($old);

                $path = $request->file($field)->store('hero-slides', 'public');
                $slide->update(["img{$n}_path" => $path]);
            }

            // Handle delete checkbox
            if ($request->boolean("delete_img{$n}")) {
                $old = $slide->{"img{$n}_path"};
                if ($old) Storage::disk('public')->delete($old);
                $slide->update(["img{$n}_path" => null, "img{$n}_label" => null, "img{$n}_url" => null]);
            }
        }
    }
}
