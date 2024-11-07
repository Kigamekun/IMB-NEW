<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\DataTables;

class DataIMBTidaklengkapController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('imb_pecahan')
                ->select('imb_pecahan.imb_induk_id', DB::raw('COUNT(*) as jumlah_pecahan_tanpa_relasi'))
                ->leftJoin('imb_induk_perum', 'imb_pecahan.imb_induk_id', '=', 'imb_induk_perum.imb_induk')
                ->whereNull('imb_induk_perum.imb_induk')
                ->groupBy('imb_pecahan.imb_induk_id')
                ->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;">
                            <a href="" class="edit btn btn-warning btn-sm">Edit</a>
                            <button type="button" class="btn btn-primary btn-sm pair-btn" data-bs-toggle="modal"
                                data-bs-target="#pairImbModal" data-pecahan-id="' . $row->imb_induk_id . '">Pair</button>
                            <form action="" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">Hapus</button>
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
        return view('IMBTidakLengkap.index', compact('imbIndukOptions'));
    }

    public function pair(Request $request)
    {

        $request->validate([
            'pecahan_id' => 'required',
        ]);

        $ImbInduk = explode(' | ', $request->imb_induk_id);

        DB::table('imb_pecahan')
            ->where('imb_induk_id', $request->pecahan_id)
            ->update(['imb_induk_id' => $ImbInduk[0]]);

        return redirect()->route('DataIMBTidakLengkap.index')->with(['success' => 'Data berhasil dipairkan','status'=>'success','message' => 'Data berhasil dipairkan']);
    }

}
