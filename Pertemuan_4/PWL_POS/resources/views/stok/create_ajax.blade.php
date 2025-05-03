<form action="{{ url('/stok/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->barang_id }}">{{ $barang->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tambah Stok</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" required
                        min="1">
                    <small id="error-stok_jumlah" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Stok</label>
                    <input type="datetime-local" name="stok_tanggal" id="stok_tanggal" class="form-control" required>
                    <small id="error-stok_tanggal" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('stok_tanggal').value = now.toISOString().slice(0, 16);

        $("#form-tambah").validate({
            rules: {
                supplier_id: {
                    required: true,
                    number: true
                },
                barang_id: {
                    required: true,
                    number: true
                },
                stok_jumlah: {
                    required: true,
                    number: true,
                    min: 1
                },
                stok_tanggal: {
                    required: true
                }
            },
            messages: {
                stok_jumlah: {
                    min: "Jumlah stok minimal 1"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    beforeSend: function() {
                        $('.btn-primary').prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                    },
                    complete: function() {
                        $('.btn-primary').prop('disabled', false).text('Simpan');
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tableStok.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyimpan data'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
