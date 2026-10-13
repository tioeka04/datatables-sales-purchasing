@extends('layouts.app')

@section('title', 'Master Produk')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-0">Master Produk</h4>
            <small class="text-muted">DataTables server-side &middot; export &middot; column visibility</small>
        </div>
        <button type="button" class="btn btn-primary" id="btnAdd">
            <i class="bi bi-plus-lg me-1"></i>Tambah Produk
        </button>
    </div>

    <div id="alertBox"></div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Kategori</label>
                    <select id="fCategory" class="form-select form-select-sm">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Supplier</label>
                    <select id="fSupplier" class="form-select form-select-sm">
                        <option value="">Semua supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Status stok</label>
                    <select id="fStock" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="low">Stok menipis (1-10)</option>
                        <option value="empty">Stok habis</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnReset">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="productsTable" class="table table-striped table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal tambah / edit --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="productForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productId">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="product_code">Kode Produk</label>
                            <input type="text" class="form-control" id="product_code" name="product_code" maxlength="20">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="product_name">Nama Produk</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" maxlength="150">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="category">Kategori</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">-- Pilih kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="supplier_id">Supplier</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">-- Tanpa supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="purchase_price">Harga Beli (Rp)</label>
                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" min="0" step="any">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="price">Harga Jual (Rp)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="any">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="stock">Stok</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" step="1">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="2" maxlength="1000"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal konfirmasi hapus --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus produk <strong id="deleteName"></strong>? Tindakan ini tidak bisa dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                        <i class="bi bi-trash me-1"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(function () {
    const urls = {
        data: @json(route('products.data')),
        store: @json(route('products.store')),
        resource: @json(url('products')),
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
        },
    });

    const rupiah = $.fn.dataTable.render.number('.', ',', 0, 'Rp ');
    const exportCols = ':visible:not(.no-export)';

    // Buang 'btn-secondary' bawaan Buttons supaya varian outline di bawah tidak bentrok.
    DataTable.Buttons.defaults.dom.button.className = 'btn';

    // ---- DataTables (server-side) + Buttons extension ----
    const table = new DataTable('#productsTable', {
        processing: true,
        serverSide: true,
        ajax: {
            url: urls.data,
            data: function (d) {
                d.category = $('#fCategory').val();
                d.supplier_id = $('#fSupplier').val();
                d.stock_status = $('#fStock').val();
            },
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        pageLength: 10,
        order: [[1, 'asc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'product_code', name: 'product_code' },
            { data: 'product_name', name: 'product_name' },
            { data: 'category', name: 'category' },
            { data: 'supplier_name', name: 'supplier_name' },
            { data: 'purchase_price', name: 'purchase_price', className: 'text-end', render: rupiah, visible: false },
            { data: 'price', name: 'price', className: 'text-end', render: rupiah },
            {
                data: 'stock', name: 'stock', className: 'text-center',
                render: function (data, type) {
                    if (type !== 'display') return data;
                    const cls = data === 0 ? 'danger' : (data <= 10 ? 'warning text-dark' : 'success');
                    return '<span class="badge bg-' + cls + '">' + data + '</span>';
                },
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'no-export text-nowrap text-center' },
        ],
        layout: {
            topStart: {
                buttons: [
                    { extend: 'copy',  text: '<i class="bi bi-clipboard"></i> Copy',    className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: exportCols } },
                    { extend: 'csv',   text: '<i class="bi bi-filetype-csv"></i> CSV',  className: 'btn btn-sm btn-outline-secondary', title: 'Data Produk', exportOptions: { columns: exportCols } },
                    { extend: 'excel', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-sm btn-outline-success', title: 'Data Produk', exportOptions: { columns: exportCols } },
                    { extend: 'pdf',   text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-sm btn-outline-danger', title: 'Data Produk', orientation: 'landscape', pageSize: 'A4', exportOptions: { columns: exportCols } },
                    { extend: 'print', text: '<i class="bi bi-printer"></i> Print',     className: 'btn btn-sm btn-outline-secondary', title: 'Data Produk', exportOptions: { columns: exportCols } },
                    { extend: 'colvis', text: '<i class="bi bi-layout-three-columns"></i> Kolom', className: 'btn btn-sm btn-outline-primary', columns: ':not(.no-export)' },
                ],
            },
            topEnd: 'search',
            bottomStart: ['pageLength', 'info'],
            bottomEnd: 'paging',
        },
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ produk',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ produk)',
            zeroRecords: 'Tidak ada produk yang cocok',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' },
        },
    });

    // ---- Filter ----
    $('#fCategory, #fSupplier, #fStock').on('change', () => table.ajax.reload());
    $('#btnReset').on('click', function () {
        $('#fCategory, #fSupplier, #fStock').val('');
        table.search('').ajax.reload();
    });

    // ---- Helpers ----
    const productModal = new bootstrap.Modal('#productModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const $form = $('#productForm');
    let deleteId = null;

    function flash(message, type = 'success') {
        const $el = $(
            '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            $('<span>').text(message).html() +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'
        );
        $('#alertBox').empty().append($el);
        setTimeout(() => $el.alert('close'), 4000);
    }

    function clearErrors() {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');
    }

    function showErrors(errors) {
        $.each(errors, function (field, messages) {
            const $input = $form.find('[name="' + field + '"]');
            $input.addClass('is-invalid');
            $input.siblings('.invalid-feedback').text(messages[0]);
        });
    }

    // ---- Create ----
    $('#btnAdd').on('click', function () {
        clearErrors();
        $form[0].reset();
        $('#productId').val('');
        $('#productModalTitle').text('Tambah Produk');
        productModal.show();
    });

    // ---- Edit ----
    $('#productsTable').on('click', '.btn-edit', function () {
        const p = $(this).data('product');
        clearErrors();
        $form[0].reset();
        $('#productId').val(p.id);
        $('#productModalTitle').text('Edit Produk');
        $.each(['product_code', 'product_name', 'category', 'supplier_id', 'purchase_price', 'price', 'stock', 'description'], function (_, f) {
            $form.find('[name="' + f + '"]').val(p[f] ?? '');
        });
        productModal.show();
    });

    // ---- Save (create / update) ----
    $form.on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        const id = $('#productId').val();
        const data = $form.serializeArray();
        if (id) data.push({ name: '_method', value: 'PUT' });

        $('#btnSave').prop('disabled', true);
        $.post(id ? urls.resource + '/' + id : urls.store, data)
            .done(function (res) {
                productModal.hide();
                flash(res.message);
                table.ajax.reload(null, false);
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    flash('Terjadi kesalahan pada server.', 'danger');
                }
            })
            .always(() => $('#btnSave').prop('disabled', false));
    });

    // ---- Delete ----
    $('#productsTable').on('click', '.btn-delete', function () {
        deleteId = $(this).data('id');
        $('#deleteName').text($(this).data('name'));
        deleteModal.show();
    });

    $('#btnConfirmDelete').on('click', function () {
        $.post(urls.resource + '/' + deleteId, { _method: 'DELETE' })
            .done(function (res) {
                deleteModal.hide();
                flash(res.message);
                table.ajax.reload(null, false);
            })
            .fail(() => flash('Gagal menghapus produk.', 'danger'));
    });
});
</script>
@endpush
