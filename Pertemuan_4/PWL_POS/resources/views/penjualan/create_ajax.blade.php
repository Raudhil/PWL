<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control"
                        required>
                    <small id="error-penjualan_tanggal" class="error-text text-danger"></small>
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
        document.getElementById('penjualan_tanggal').value = now.toISOString().slice(0, 16);

        $("#form-tambah").validate({
            rules: {
                pembeli: {
                    required: true
                },
                penjualan_kode: {
                    required: true
                },
                penjualan_tanggal: {
                    required: true
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
                            tablePenjualan.ajax.reload();
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
