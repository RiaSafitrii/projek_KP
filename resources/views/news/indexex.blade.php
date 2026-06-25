<!DOCTYPE html>
<html>
<head>
    <title>Laravel DataTables AJAX with Modal</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<table id="news-table" class="display">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Publish Date</th>
            <th>Operator</th>
            <th>Status</th>
            <th>Category</th>
            <th>Author</th>
            <th>Comments Count</th>
            <th>Action</th> <!-- tombol modal -->
        </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newsModalLabel">Detail News</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Title:</strong> <span id="modal-title"></span></p>
        <p><strong>Publish Date:</strong> <span id="modal-publish-date"></span></p>
        <p><strong>Operator:</strong> <span id="modal-operator"></span></p>
        <p><strong>Status:</strong> <span id="modal-status"></span></p>
        <p><strong>Category:</strong> <span id="modal-category"></span></p>
        <p><strong>Author:</strong> <span id="modal-author"></span></p>
        <p><strong>Comments Count:</strong> <span id="modal-comments-count"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function() {
    var table = $('#news-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('mstnews.data') }}',
        columns: [
            { data: null, name: 'no', orderable: false, searchable: false }, // nomor urut
            { data: 'title', name: 'news.title' },
            { data: 'publish_date', name: 'news.publish_date' },
            { data: 'operator', name: 'news.operator' },
            { data: 'status', name: 'news.status' },
            { data: 'name_category', name: 'categories.name' },
            { data: 'penulis', name: 'author.name' },
            { data: 'comments_count', name: 'comments_count' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return '<button class="btn btn-primary btn-sm btn-detail">Detail</button>';
                }
            }
        ],
        order: [[1, 'asc']],
        columnDefs: [{
            targets: 0,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }]
    });

    // Event delegation untuk tombol detail (karena tombolnya di-generate DataTables)
    $('#news-table tbody').on('click', 'button.btn-detail', function () {
        var data = table.row($(this).closest('tr')).data();
        // Isi modal dengan data
        $('#modal-title').text(data.title);
        $('#modal-publish-date').text(data.publish_date);
        $('#modal-operator').text(data.operator);
        $('#modal-status').text(data.status);
        $('#modal-category').text(data.name_category);
        $('#modal-author').text(data.penulis);
        $('#modal-comments-count').text(data.comments_count);

        // Tampilkan modal
        var myModal = new bootstrap.Modal(document.getElementById('newsModal'));
        myModal.show();
    });
});
</script>

</body>
</html>
