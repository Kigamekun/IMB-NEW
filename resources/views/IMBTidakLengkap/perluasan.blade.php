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
                    <h3 class="text-3xl font-bold">Data Perluasan Tidak Lengkap</h3>
                    <br>
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>IMB Pecahan</th>
                                    <th>Jumlah Perluasan Tanpa Relasi</th>
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
                url: "{{ route('DataIMBTidakLengkap.perluasan') }}",
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
                    data: 'imb_pecahan',
                    title: 'IMB Induk'
                },
                {
                    data: 'jumlah_perluasan_tanpa_relasi',
                    title: 'Jumlah Perluasan Tanpa Relasi'
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
                    <h5 class="modal-title" id="pairImbModalLabel">Pair IMB Perluasan with Existing IMB Pecahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pairImbForm" method="POST" action="{{ route('DataIMBTidakLengkap.pair-perluasan') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="perluasanId" name="perluasan_id">
                        <div class="mb-3">
                            <label for="imbInduk" class="form-label">Select IMB Pecahan</label>
                            <select class="form-control select2-imb-pecahan" id="imbInduk" name="imb_pecahan"
                                style="width: 100%;">
                                <option value="">Choose IMB Pecahan</option>
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
                // Extract perluasan_id and set it in the hidden input field
                const perluasanId = button.getAttribute('data-perluasan-id');
                document.getElementById('perluasanId').value = perluasanId;

                // Initialize the Select2 dropdown with the modal as its parent
                $('.select2-imb-pecahan').select2({
                    width: '100%',
                    placeholder: 'Pilih IMB Pecahan',
                    minimumInputLength: 2,
                    dropdownParent: $('#pairImbModal'),  // Set modal as parent for dropdown
                    ajax: {
                        url: "{{ route('master.imb-pecahan') }}", // Route to fetch IMB Induk options
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
                $('.select2-imb-pecahan').val(null).trigger('change');
            });
        });
    </script>
@endsection
