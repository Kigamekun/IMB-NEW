<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportIMBIndukPerum;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IMBIndukPerum;
use DataTables;

class IMBController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBIndukPerum::latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex " style="gap:10px;">
                        <div>
                        <a href="' . route('IMB.destroy', ['id' => $row->id]) . '" class="edit btn btn-info ">Tambahkan Item Lab</a>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('IMB.index');
    }

    public function import()
    {
        return view('IMB.import');
    }

    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportIMBIndukPerum(), $file);
        return redirect()->route('IMB.index');
    }
}
