<a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $user->id }}">
    Delete
</button>
