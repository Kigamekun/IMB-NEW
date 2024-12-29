<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Data Surat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="width:700px">
    <div class="container" >
        <h2>Preview Data Surat</h2>
        <table class="table table-bordered" id="suratTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>No SK</th>
                    <th>Tanggal</th>
                    <th>Pemohon</th>
                    <th>Atas Nama</th>
                    <th>Alamat Pemohon</th>
                    <th>No Register</th>
                    <th>No IMBG</th>
                    <th>Lokasi Bangunan</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan</th>
                    <th>Kelurahan</th>
                    <th>Jenis</th>
                    {{-- <th>Status</th> --}}
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                <!-- Looping data dari controller -->
                <?php $no = 1; ?>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $row->tahun }}</td>
                        <td>{{ $row->nomorSurat }}</td>
                        <td>{{ $row->tanggalSurat }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->bertindak_atas_nama }}</td>
                        <td>{{ $row->alamat }}</td>
                        <td>{{ $row->registerNomor }}</td>
                        <td>{{ $row->imbgNomor }}</td>
                        <td>{{ $row->lokasi }}</td>
                        <td>{{ $row->nama_kabupaten }}</td>
                        <td>{{ $row->nama_kecamatan }}</td>
                        <td>{{ $row->nama_kelurahan }}</td>
                        <td>{{ $row->jenisSurat }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <script>
        var table = $('#suratTable').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('surat.previewIndex') }}",
                data: function(d) {
                    d.nomor_surat = $('#nomorSurat').val();
                    d.nama_pemohon = $('#namaPemohonSurat').val();
                    d.nomor_imbg = $('#nomorIMBG').val();
                    d.lokasi_bangunan = $('#lokasiPemohonSurat').val();
                    d.kabupaten_pemohon = $('#kabupatenPemohonSurat').val();
                    d.kecamatan_pemohon = $('#kecamatanPemohonSurat').val();
                    d.kelurahan_pemohon = $('#kelurahanPemohonSurat').val();
                },
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

            columns: [


                {
                    data: 'DT_RowIndex',
                    title: 'No',

                },
                {
                    data: 'tahun',
                    title: 'Tahun'
                },
                {
                    data: 'nomorSurat',
                    title: 'No SK'
                },
                {
                    data: 'tanggalSurat',
                    title: 'Tanggal'
                },
                {
                    data: 'nama',
                    title: 'Pemohon'
                },
                {
                    data: 'bertindak_atas_nama',
                    title: 'Atas Nama'
                },
                {
                    data: 'alamat',
                    title: 'Alamat Pemohon'
                },
                {
                    data: 'registerNomor',
                    title: 'No Register'
                },
                {
                    data: 'imbgNomor',
                    title: 'No IMBG'
                },
                {
                    data: 'lokasi',
                    title: 'Lokasi Bangunan'
                },
                {
                    data: 'nama_kabupaten',
                    title: 'Kabupaten / Kota'
                },
                {
                    data: 'nama_kecamatan',
                    title: 'Kecamatan'
                },
                {
                    data: 'nama_kelurahan',
                    title: 'Kelurahan'
                },
                {
                    data: 'jenisSurat',
                    title: 'Jenis'
                },
                // {
                //     data: '#',
                //     title: 'Status'
                // },
                {
                    data: 'action',
                    title: 'Action'
                }

            ],
            // order: [
            //     [2, 'desc']
            // ]
        });
    </script> --}}
</body>
</html>
