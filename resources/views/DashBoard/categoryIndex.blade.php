@extends('DashBoard.header')

@section('title', 'Categories')

@section('content')

    <body>
        @csrf

        <div class="category-container">
        <!-- Create New Category Button and Form -->

            <div id="createCategoryForm" >
                @if (session('success'))
                <div class="alert alert-success">{{ __('category_created_successfully') }}</div>
                @endif

                <form id="CategoryCreate" action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="category">
                        <label for="name">{{ __('Create New Category') }}:</label>
                        <input type="text" id="name" name="name" class="category_name" value="{{ old('name') }}">
                    </div>

                    <td>
                        <input type="hidden" name="id" id="id" value="{{ $id }}">
                    </td>




                    @error('name')
                        <div class="alert alert-danger mt-2">{{ __('error_name') }}: {{ $message }}</div>
                    @enderror
                </form>
            </div>

        <!-- Search Input Field -->
        <div class="search-container">
            <input type="text" id="searchBox" placeholder="{{ __('search_categories') }}" autocomplete="off">
            <img src="{{ asset('PostBlug/searchIcon.png') }}" alt="{{ __('search_icon_alt') }}">
        </div>

        <!-- Horizontal Schedule Container -->
        <div class="table-container">
            <h1 style="text-align: center;">{{ __('categories_view') }}</h1>

            @include('DashBoard.partials.categoryIndex', ['categories' => $categories])
        </div>

         <!-- Pagination -->
    <div class="d-flex justify-content-center">
        <div id="pagination-links">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>



    </body>
</div>


    <script>
        $(function() {

       // Ensure CSRF token is sent with every AJAX request
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });


       // Handle Category Creation via AJAX
       $('#CategoryCreate').submit(function(e) {
           e.preventDefault(); // Prevent form submission

           let categoryName = $('#name').val();
           let id=$('#id').val();

           $.ajax({
               url: '{{ route('categories.store') }}',
               type: 'POST',
               data: {
                   name: categoryName,
                   id:id,
                   _token: '{{ csrf_token() }}' // CSRF token for security
               },
               success: function(response) {
                   if (response.success) {
                       alert('{{ __('category_created_successfully') }}');

                       console.log(response.data);
                       $('#category-index').html(response.data);


                       $('#name').val('');
                   } else {
                       alert('{{ __('failed_to_create_category') }}');
                   }
               },
               error: function(xhr) {
                   if (xhr.status === 422) {
                       const errors = xhr.responseJSON.errors;
                       if (errors.name_en) {
                           alert('English Name Error: ' + errors.name_en.join(', '));
                       }
                       if (errors.name_ar) {
                           alert('Arabic Name Error: ' + errors.name_ar.join(', '));
                       }
                       if (errors.color) {
                           alert('{{ __('error_color') }}: ' + errors.color.join(', '));
                       }
                   } else {
                       alert('{{ __('error_occurred') }}');
                   }
               }
           });
       });

       // Handle Category Update via AJAX
       function updateCategory(categoryId, categoryName) {
           $.ajax({
               url: '{{ route('categories.update', ':id') }}'.replace(':id', categoryId),
               type: 'PUT',
               data: {
                   name: categoryName,
                   _token: '{{ csrf_token() }}' // CSRF token
               },
               success: function(response) {
                   alert('{{ __('category_updated_successfully') }}');
               },
               error: function(xhr) {
                   alert('An error occurred. Please try again.');
               }
           });
       }

       // Handle Category Deletion via AJAX
       function deleteCategory(categoryId) {
           if (confirm('{{ __('confirm_delete_category') }}')) {
               $.ajax({
                   url: '{{ route('categories.destroy', ':id') }}'.replace(':id', categoryId),
                   type: 'DELETE',
                   data: {
                       _token: '{{ csrf_token() }}' // CSRF token
                   },
                   success: function(response) {
                       if (response.success) {
                           alert('{{ __('deleted_successfully') }}');
                           $(`#category-${categoryId}`).remove(); // Remove category row
                       } else {
                           alert('{{ __('permission_denied') }}');
                       }
                   },
                   error: function() {
                       alert('{{ __('error_occurred') }}');
                   }
               });
           }
       }

       // Update Category Button Click
       $(document).on('click', '.update-category-button', function(e) {
           e.preventDefault();
           let categoryId = $(this).data('id');
           let categoryRow = $(this).closest('tr');
           let categoryName = categoryRow.find('input[name="name"]').val();
           updateCategory(categoryId, categoryName);
       });

       // Delete Category Button Click
       $(document).on('click', '.delete-category-button', function(e) {
           e.preventDefault();
           let categoryId = $(this).data('id');
           deleteCategory(categoryId);
       });



       // Handle Pagination Links Click
       $(document).on('click', '.pagination a', function(e) {
           e.preventDefault();
           const page = $(this).attr('href').split('page=')[1];
           const parent_id = $(this).data('parent-id');


           fetchCategory(parent_id, page);
       });

       // Search Box Input Event
       const searchBox = $('#searchBox');


       searchBox.on('input', function() {
           const query = this.value.toLowerCase().trim();

               $.ajax({
                   url: '{{ route('categories.search') }}',
                   type: 'POST',
                   data: { search: query },
                   success: function(response) {
                       if (response.success) {
                           $('#category-index').html(response.data);
                       } else {
                           categoriesTableBody.html('<tr><td colspan="4">No categories found.</td></tr>');
                       }
                   },
                   error: function() {
                       alert('An error occurred while searching. Please try again.');
                   }
               });

       });

       // Function to fetch categories for a specific page
       function fetchCategory(parent_id, page) {
           $.ajax({
               url: '{{ route('categories.paginate') }}',
               type: 'POST',
               data: {
                   parent_id: parent_id,
                   page: page
               },
               success: function(response) {
                console.log(response);
                   $('#category-index').html(response.data);  // Only update the tbody

               },
               error: function() {
                   alert('An error occurred while fetching categories. Please try again.');
               }
           });
       }

       });

       </script>



@endsection
