{{-- Single category table row. $depth indents sub-categories. --}}
<tr>
    <td>
        @if ($category->image_url)
            <img src="{{ $category->image_url }}" alt="" width="38" height="38" class="rounded">
        @else
            <span class="avatar avatar-sm bg-light text-muted"><i class="bx bx-category"></i></span>
        @endif
    </td>
    <td>
        @if ($depth) <span class="text-muted">↳ &nbsp;</span> @endif
        <span class="fw-semibold">{{ $category->name }}</span>
        <div><code class="fs-11 text-muted">{{ $category->slug }}</code></div>
    </td>
    <td>{{ $category->parent?->name ?? '—' }}</td>
    <td><span class="badge bg-{{ $category->show_on_home ? 'success' : 'light text-muted' }}">{{ $category->show_on_home ? 'Yes' : 'No' }}</span></td>
    <td><span class="badge bg-{{ $category->is_featured ? 'info' : 'light text-muted' }}">{{ $category->is_featured ? 'Yes' : 'No' }}</span></td>
    <td><span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">{{ $category->is_active ? 'Active' : 'Off' }}</span></td>
    <td>{{ $category->products()->count() }}</td>
    <td class="text-end">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-icon btn-info-light"><i class="bx bx-edit"></i></a>
        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Delete this category?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-icon btn-danger-light"><i class="bx bx-trash"></i></button>
        </form>
    </td>
</tr>
