<!DOCTYPE html>
<html>
<head>
    <title>Book Store</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Book Store</h1>

        <div id="message" class="alert" style="display: none;"></div>

        <div class="mb-4">
            <h2>Add New Book</h2>
            <form id="bookForm">
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="publish_date">Publish Date</label>
                    <input type="date" class="form-control" id="publish_date" name="publish_date" required>
                </div>
                <input type="hidden" id="book_id">
                <button type="submit" id="submitBtn" class="btn btn-primary">Add Book</button>
                <button type="button" id="saveBtn" class="btn btn-success" style="display: none;">Save</button>
            </form>
        </div>

        <div>
            <h2>Books List</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publish Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTable">
                    <!-- Books will be listed here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            fetchBooks();

            // Fetch all books
            function fetchBooks() {
                $.ajax({
                    url: "{{ url('/api/books') }}",
                    method: 'GET',
                    success: function(response) {
                        var booksTable = $('#booksTable');
                        booksTable.empty();
                        response.forEach(function(book) {
                            booksTable.append(`
                                <tr>
                                    <td>${book.id}</td>
                                    <td>${book.name}</td>
                                    <td>${book.author}</td>
                                    <td>${book.publish_date}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${book.id}" data-name="${book.name}" data-author="${book.author}" data-publish_date="${book.publish_date}">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${book.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            // Show message
            function showMessage(type, message) {
                $('#message').removeClass().addClass('alert alert-' + type).text(message).show();
                setTimeout(function() {
                    $('#message').fadeOut('slow');
                }, 3000);
            }

            // Add a new book
            $('#bookForm').on('submit', function(e) {
                e.preventDefault();
                var name = $('#name').val();
                var author = $('#author').val();
                var publish_date = $('#publish_date').val();
                $.ajax({
                    url: "{{ url('/api/books') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        author: author,
                        publish_date: publish_date
                    },
                    success: function(response) {
                        $('#bookForm')[0].reset();
                        fetchBooks();
                        showMessage('success', 'Book added successfully.');
                    },
                    error: function(response) {
                        showMessage('danger', 'Failed to add book.');
                    }
                });
            });

            // Delete a book
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/api/books') }}/" + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        fetchBooks();
                        showMessage('success', 'Book deleted successfully.');
                    },
                    error: function(response) {
                        showMessage('danger', 'Failed to delete book.');
                    }
                });
            });

            // Edit a book
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var author = $(this).data('author');
                var publish_date = $(this).data('publish_date');
                $('#name').val(name);
                $('#author').val(author);
                $('#publish_date').val(publish_date);
                $('#book_id').val(id);
                $('#submitBtn').hide();
                $('#saveBtn').show();
            });

            // Save edited book
            $('#saveBtn').on('click', function() {
                var id = $('#book_id').val();
                var name = $('#name').val();
                var author = $('#author').val();
                var publish_date = $('#publish_date').val();
                $.ajax({
                    url: "{{ url('/api/books') }}/" + id,
                    method: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        author: author,
                        publish_date: publish_date
                    },
                    success: function(response) {
                        $('#bookForm')[0].reset();
                        $('#submitBtn').show();
                        $('#saveBtn').hide();
                        fetchBooks();
                        showMessage('success', 'Book updated successfully.');
                    },
                    error: function(response) {
                        showMessage('danger', 'Failed to update book.');
                    }
                });
            });
        });
    </script>
</body>
</html>
