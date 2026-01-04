@extends('layouts.app')

@section('content')
  <main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
          <div class="col-sm-6">
            <h3 class="mb-0">Categories</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Category</li>
            </ol>
          </div>
        </div>
        <!--end::Row-->
      </div>
      <!--end::Container-->
    </div>

    <!--Body code-->
    <div class="app-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-4">
              <div class="card-header">
                <h3 class="card-title">Category List</h3>
                <div class="card-tools">
                  <a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryModal" class="btn btn-sm btn-primary" id="add-category-btn">Add Category</a>
                </div>
              </div>
              <div class="card-body">
                <table id="category-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Add/Edit Category Modal -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="category-form">
            <input type="hidden" id="category_id" name="category_id" value="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mb-3">
              <label for="category_name" class="form-label">Category Name</label>
              <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-primary" id="save-category-btn">Save Category</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal -->

  <div class="flashMessage alert alert-success" style="display: none;"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      // Store the current mode (add or edit)
      let currentMode = 'add';
      let currentCategoryId = null;

      // Function to fetch and display categories
      function fetchCategories() {
        $.ajax({
          url: '{{ url("admin/category/data") }}',
          method: 'GET',
          dataType: 'json',
          success: function(data) {
            var tbody = $('#category-table tbody');
            tbody.empty(); // Clear existing data

            $.each(data, function(index, category) {
              var createdAt = dayjs(category.created_at).format('YYYY-MM-DD | h:mm A');
              // Use proper template literal syntax
              var row = `<tr>
                <td>${index + 1}</td>
                <td>${category.category_name}</td>
                <td>${createdAt}</td>
                <td>
                  <button href="#" data-id="${category.id}" class="btn btn-sm btn-success me-1 edit-btn"><i class="fas fa-edit"></i></button>
                  <button href="#" data-id="${category.id}" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
                </td>
              </tr>`;
              tbody.append(row);
            });
          },
          error: function(xhr, status, error) {
            console.error('Error fetching categories:', error);
          }
        });
      }

      // Handle edit button click
      $(document).on('click', '.edit-btn', function() {
        currentMode = 'edit';
        currentCategoryId = $(this).data('id');
        
        // Change modal title
        $('#addCategoryModalLabel').text('Edit Category');
        $('#save-category-btn').text('Update Category');
        
        // Fetch category data
        $.ajax({
          url: `{{ url("admin/category/edit") }}/${currentCategoryId}`,
          method: 'GET',
          dataType: 'json',
          success: function(data) {
            $('#category_name').val(data.category_name);
            $('#addCategoryModal').modal('show');
          },
          error: function(xhr, status, error) {
            console.error('Error fetching category for edit:', error);
          }
        });
      });

      // Handle delete button click
      $(document).on('click', '.delete-btn', function() {
        if (confirm('Are you sure you want to delete this category?')) {
          const id = $(this).data('id');
          $.ajax({
            url: `{{ url("admin/category/delete") }}/${id}`,
            method: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              $('.flashMessage').text(response.success).fadeIn().delay(2000).fadeOut();
              fetchCategories(); // Refresh the category list
            },
            error: function(xhr, status, error) {
              console.error('Error deleting category:', error);
            }
          });
        }
      });

      // Reset modal when Add Category button is clicked
      $('#add-category-btn').on('click', function() {
        currentMode = 'add';
        currentCategoryId = null;
        $('#addCategoryModalLabel').text('Add New Category');
        $('#save-category-btn').text('Save Category');
        $('#productForm')[0].reset();
      });

      // Handle form submission for both add and update
      $('#category-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
          category_name: $('#category_name').val(),
          _token: '{{ csrf_token() }}'
        };
        
        let url, method;
        
        if (currentMode === 'edit') {
          url = `{{ url("admin/category/update") }}/${currentCategoryId}`;
          method = 'PUT'; // or POST depending on your route
        } else {
          url = '{{ url("admin/category/store") }}';
          method = 'POST';
        }
        
        $.ajax({
          url: url,
          method: method,
          data: formData,
          success: function(response) {
            $('#addCategoryModal').modal('hide');
            $('#category-form')[0].reset();
            $('.flashMessage').text(response.success).fadeIn().delay(2000).fadeOut();
            fetchCategories(); // Refresh the category list
          },
          error: function(xhr, status, error) {
            console.error('Error saving category:', error);
          }
        });
      });

      // Initial fetch
      fetchCategories();
    });
  </script>
@endsection