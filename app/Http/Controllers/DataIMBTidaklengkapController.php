<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\DataTables;

class DataIMBTidaklengkapController extends Controller
{
    public function pecahan(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('imb_pecahan')
                ->select('imb_pecahan.imb_induk_id', DB::raw('COUNT(*) as jumlah_pecahan_tanpa_relasi'))
                ->leftJoin('imb_induk_perum', 'imb_pecahan.imb_induk_id', '=', 'imb_induk_perum.imb_induk')
                ->leftJoin('imb_induk_non_perum', 'imb_pecahan.imb_induk_id', '=', 'imb_induk_non_perum.imb_induk')
                ->whereNull('imb_induk_perum.imb_induk')
                ->whereNull('imb_induk_non_perum.imb_induk')
                ->groupBy('imb_pecahan.imb_induk_id')
                ->get();

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;">

                            <button type="button" class="btn btn-primary btn-sm pair-btn" data-toggle="modal"
                                data-target="#pairImbModal" data-pecahan-id="' . $row->imb_induk_id . '">Pair</button>
                            <form action="" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        // Fetching options from both imb_induk_perum and imb_induk_non_perum
        $imbIndukOptions = \DB::table('imb_induk_perum')
            ->select('imb_induk')
            ->union(
                \DB::table('imb_induk_non_perum')
                    ->select('imb_induk')
            )
            ->get();

        return view('IMBTidakLengkap.pecahan', compact('imbIndukOptions'));
    }

    public function perluasan(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('imb_perluasan')
                ->select('imb_perluasan.imb_pecahan', DB::raw('COUNT(*) as jumlah_perluasan_tanpa_relasi'))
                ->leftJoin('imb_pecahan', 'imb_perluasan.imb_pecahan', '=', 'imb_pecahan.imb_pecahan')
                ->whereNull('imb_pecahan.imb_pecahan')
                ->groupBy('imb_perluasan.imb_pecahan')
                ->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;">
                            <button type="button" class="btn btn-primary btn-sm pair-btn" data-toggle="modal"
                                data-target="#pairImbModal" data-perluasan-id="' . $row->imb_pecahan . '">Pair</button>
                            <form action="" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $imbIndukOptions = \DB::table('imb_induk_perum')
            ->select('imb_induk_perum.imb_induk')
            ->get();
        return view('IMBTidakLengkap.perluasan', compact('imbIndukOptions'));
    }

    public function pairPecahan(Request $request)
    {

        $request->validate([
            'pecahan_id' => 'required',
        ]);

        $ImbInduk = explode(' | ', $request->imb_induk_id);

        DB::table('imb_pecahan')
            ->where('imb_induk_id', $request->pecahan_id)
            ->update(['imb_induk_id' => $ImbInduk[0]]);

        return redirect()->route('DataIMBTidakLengkap.pecahan')->with(['success' => 'Data berhasil dipairkan', 'status' => 'success', 'message' => 'Data berhasil dipairkan']);
    }

    public function pairPerluasan(Request $request)
    {

        $request->validate([
            'perluasan_id' => 'required',
        ]);


        $imbPecahan = explode(' | ', $request->imb_pecahan);

        DB::table('imb_perluasan')
            ->where('imb_pecahan', $request->perluasan_id)
            ->update(['imb_pecahan' => $imbPecahan[0]]);



        return redirect()->route('DataIMBTidakLengkap.perluasan')->with(['success' => 'Data berhasil dipairkan', 'status' => 'success', 'message' => 'Data berhasil dipairkan']);
    }

}
