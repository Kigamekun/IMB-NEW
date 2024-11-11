<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;


class TujuanSuratController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('master_tujuan_surat') ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = '<div class="d-flex" style="gap:5px;">';
                    $btn .= '
                    <button type="button" title="EDIT" class="btn btn-sm  btn-warning" data-bs-toggle="modal" data-bs-target="#updateData"
                    data-nama="' . $row->nama . '"
                    data-url="' . route('tujuan-surat.update', ['id' => $id]) . '"
                    >
                        Edit
                    </button>';
                    $btn .= '
                    <form id="deleteForm" action="' . route('tujuan-surat.destroy', ['id' => $id]) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                                <button type="button" title="DELETE" class="btn btn-sm btn-danger btn-delete" onclick="confirmDelete(event)">
                                    Delete
                                </button>
                            </form>
                    </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.tujuan-surat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        \DB::table('master_tujuan_surat')->insert([
            'nama' => $request->nama,
        ]);
        return redirect()->back()->with(['message' => 'Tujuan Surat berhasil ditambahkan', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'nama' => 'required',
        ]);
        \DB::table('master_tujuan_surat')->where('id', $id)->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('tujuan-surat.index')->with(['message' => 'Tujuan Surat berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        \DB::table('master_tujuan_surat')->where('id', $id)->delete();
        return redirect()->route('tujuan-surat.index')->with(['message' => 'Tujuan Surat berhasil di delete', 'status' => 'success']);
    }
}
