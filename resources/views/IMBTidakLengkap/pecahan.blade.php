@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
    <style>
        th {
            white-space: nowrap;
        }
    </style>
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Data Pecahan Tidak Lengkap</h3>
                    <br>
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>IMB Induk</th>
                                    <th>Jumlah Pecahan Tanpa Relasi</th>
                                    <th>Action</th>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script>
        $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('DataIMBTidakLengkap.pecahan') }}",
                dataSrc: function(res) {
                    if (res.code == 5500) {
                        console.log(res.data);
                        return InternalServerEror()
                    } else {
                        console.log(res.data);
                        return res.data
                    }
                },
                error: function() {
                    return InternalServerEror()
                }
            },
            columns: [{
                    data: 'imb_induk_id',
                    title: 'IMB Induk'
                },
                {
                    data: 'jumlah_pecahan_tanpa_relasi',
                    title: 'Jumlah Pecahan Tanpa Relasi'
                },
                {
                    data: 'action',
                    title: 'Action'
                }
            ],
            order: [
                [2, 'desc']
            ]
        });
    </script>
@endsection

@section('modal')
    <!-- Pairing Modal -->
    <div class="modal fade" id="pairImbModal" tabindex="-1" aria-labelledby="pairImbModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pairImbModalLabel">Pair IMB Pecahan with Existing IMB Induk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pairImbForm" method="POST" action="{{ route('DataIMBTidakLengkap.pair-pecahan') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="pecahanId" name="pecahan_id">
                        <div class="mb-3">
                            <label for="imbInduk" class="form-label">Select IMB Induk</label>
                            <select class="form-control select2-imb-induk" id="imbInduk" name="imb_induk_id"
                                style="width: 100%;">
                                <option value="">Choose IMB Induk</option>
                                @foreach ($imbIndukOptions as $imbInduk)
                                    <option value="{{ $imbInduk->imb_induk }}">{{ $imbInduk->imb_induk }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Pairing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 inside the modal
            $('#pairImbModal').on('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                const button = event.relatedTarget;
                // Extract pecahan_id and set it in the hidden input field
                const pecahanId = button.getAttribute('data-pecahan-id');
                document.getElementById('pecahanId').value = pecahanId;

                // Initialize the Select2 dropdown with the modal as its parent
                $('.select2-imb-induk').select2({
                    width: '100%',
                    placeholder: 'Pilih IMB Induk',
                    minimumInputLength: 2,
                    dropdownParent: $('#pairImbModal'),  // Set modal as parent for dropdown
                    ajax: {
                        url: "{{ route('master.imb-induk') }}", // Route to fetch IMB Induk options
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,       // Search query
                                page: params.page || 1  // Pagination support
                            };
                        },
                        processResults: function(data, params) {
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                }),
                                pagination: {
                                    more: (params.page * 10) < data.total_count
                                }
                            };
                        },
                        cache: true
                    }
                });
            });

            // Clear the Select2 dropdown and hidden input when the modal is closed
            $('#pairImbModal').on('hidden.bs.modal', function() {
                $('.select2-imb-induk').val(null).trigger('change');
            });
        });
    </script>
@endsection
