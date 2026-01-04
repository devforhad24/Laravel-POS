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
                        <h3 class="mb-0">Products</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
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
                                <h3 class="card-title">Product List</h3>
                                <div class="card-tools">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal"
                                        class="btn btn-sm btn-primary">Add Product</a>
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

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <select name="category_id" id="category_id" class="form-select" required>

                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="product_code" class="form-label">Product Code</label>
                            <input type="text" name="product_code" id="product_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name_product" class="form-label">Product Name</label>
                            <input type="text" name="name_product" id="name_product" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" name="brand" id="brand" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" id="purchase_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" name="selling_price" id="selling_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" name="discount" id="discount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="save-category-btn">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <div class="flashMessage alert alert-success" style="display: none;"></div>

    <div class="flashMessage alert alert-success" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#productForm').on('submit', function (e) {
            e.preventDefault();
            
            // Hide any existing messages and remove alert-danger class
            $('.flashMessage').hide().removeClass('alert-danger');

            const formData = $(this).serialize();

            $.ajax({
                url: '{{ route("product.store") }}',
                type: 'POST',
                data: formData,

                success: function (response) {
                    // Show success message with proper styling
                    $('.flashMessage').addClass('alert-success').html(response.message).fadeIn();

                    // Wait 2 seconds, then execute the following code
                    setTimeout(function() {
                        // Hide the modal
                        $('#addProductModal').modal('hide');
                        // Reset the form
                        $('#productForm')[0].reset();
                        // Hide the flash message for the next use
                        $('.flashMessage').fadeOut();
                    }, 1000); // 2000ms = 2 seconds
                },
                error: function (xhr) {
                    // If there are validation errors, show them
                    if (xhr.status === 422) { // 422 Unprocessable Entity
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for(const key in errors){
                            // errors[key] is an array, so we join it
                            errorMessage += `<li>${errors[key].join(' ')}</li>`;
                        }
                        $('.flashMessage').addClass('alert-danger').html(`<ul>${errorMessage}</ul>`).fadeIn();
                    } else {
                        // For other server errors, show a generic message
                        $('.flashMessage').addClass('alert-danger').html('An error occurred. Please try again.').fadeIn();
                    }
                }
            });
        });
    });
</script>
@endsection