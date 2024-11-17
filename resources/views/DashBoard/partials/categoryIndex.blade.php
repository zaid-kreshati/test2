    <div id="category-index">
        <div class="category-index card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="categoriesTable">
                        <thead class="category-table-header">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Nested Categories') }}</th>
                                <th scope="col" class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categories as $category)
                                <tr id="category-{{ $category->id }}">
                                    <td class="text-center">{{ $category->id }}</td>
                                    <td>
                                        <input type="text" name="name" value="{{ $category->name }}"
                                            class="form-control form-control-sm" required>
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('nestedCategories', ['id' => $category->id]) }}"
                                            class="btn btn-info">{{ __('Nested Categories') }}</a>

                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="update-category-button btn btn-sm btn-primary"
                                                data-id="{{ $category->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="delete-category-button btn btn-sm btn-danger"
                                                data-id="{{ $category->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

