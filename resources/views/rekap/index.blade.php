@extends('layouts.base')


@section('content')
    <style>
        th {
            white-space: nowrap;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">


    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h1 class="text-center">Rekap IMB</h1>
                        <br>


                        <form id="rekapForm" method="POST" action="{{ route('rekap.imb-submit') }}">
                            @csrf
                            <!-- Filter Input -->
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="nama_pengembang">Nama Pengembang</label>
                                        <input type="text" id="nama_pengembang" name="nama_pengembang"
                                            class="form-control" placeholder="Masukkan nama pengembang...">
                                    </div>
                                </div>
                                <div class="col-md-6 ">

                                    <div class="form-group">
                                        <label for="nama_perumahan">Nama Perumahan</label>
                                        <input type="text" id="nama_perumahan" name="nama_perumahan" class="form-control"
                                            placeholder="Masukkan nama perumahan...">
                                    </div>
                                </div>

                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="no_imb_induk">No IMB Induk</label>
                                        <input type="text" id="no_imb_induk" name="no_imb_induk" class="form-control"
                                            placeholder="Masukkan No IMB Induk...">
                                    </div>
                                </div>
                                <div class="col-md-6 ">

                                    <div class="form-group">
                                        <label for="no_imb_pecahan">No IMB Pecahan</label>
                                        <input type="text" id="no_imb_pecahan" name="no_imb_pecahan" class="form-control"
                                            placeholder="Masukkan No IMB Pecahan...">
                                    </div>
                                </div>
                                <div class="col-md-6 ">

                                    <div class="form-group">
                                        <label for="no_imb_perluasan">No IMB Perluasan</label>
                                        <input type="text" id="no_imb_perluasan" name="no_imb_perluasan"
                                            class="form-control" placeholder="Masukkan No IMB Perluasan...">
                                    </div>
                                </div>
                                <div class="col-md-6 ">

                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <input type="text" id="tahun" name="tahun" class="form-control"
                                            placeholder="Masukkan tahun...">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <br>
                    <br>
                    <!-- Buttons -->
                    <div class="row text-center">
                        <div class="col-md-4">
                            <button type="submit" name="submit_type" value="register" class="btn btn-primary btn-block">
                                Register IMB Pertahun
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="submit_type" value="induk" class="btn btn-success btn-block">
                                Detail IMB Induk
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="submit_type" value="pecahan" class="btn btn-warning btn-block">
                                Detail IMB Pecahan
                            </button>
                        </div>
                    </div>
                    </form>





                </div>


            </div>

        </div>
    </div>
    </div>
@endsection



@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#IMBTable').DataTable();
        });
    </script>

    <script>
        function sendAction(type) {
            // Isi input hidden untuk "type"
            const form = document.getElementById('rekapForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'type';
            hiddenInput.value = type;
            form.appendChild(hiddenInput);

            // Submit form
            form.submit();
        }
    </script>
@endsection
