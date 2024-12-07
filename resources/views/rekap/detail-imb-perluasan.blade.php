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
                        <h1 class="text-left">Rekap Register IMB Per Tahun</h1>
                        <h4 class="text-left">Detail IMB Perluasan</h4>
                        <br>


                        <form id="rekapForm" method="GET" action="{{ route('rekap.DetailIMBPerluasanList') }}">
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="imbPecahan">IMB Pecahan</label>
                                        <input type="text" id="imbPecahan" name="imbPecahan"
                                            class="form-control" placeholder="Masukkan IMB Pecahan...">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaPerumahan">Nama Perumahan</label>
                                        <input type="text" id="namaPerumahan" name="namaPerumahan"
                                            class="form-control" placeholder="Masukkan IMB Pecahan...">
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <select id="tahun" name="tahun" class="form-control">
                                            <option value="">Pilih Tahun...</option>
                                            <?php
                                                $currentYear = date('Y'); // Tahun sekarang
                                                $startYear = $currentYear - 50; // 50 tahun ke belakang

                                                for ($year = $currentYear; $year >= $startYear; $year--) {
                                                    echo "<option value='$year'>$year</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <br>
                            <br>
                            <!-- Buttons -->
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <button type="submit" name="submit_type" value="induk" class="btn btn-primary btn-block">
                                        Detail IMB Pecahan
                                    </button>
                                </div>
                            </div>
                    </div>

                    <br>
                    <br>
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
