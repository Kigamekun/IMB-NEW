<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class JenisNonPerumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('master_jenis_non_perum') ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = '<div class="d-flex" style="gap:5px;">';
                    $btn .= '
                    <button type="button" title="EDIT" class="btn btn-sm  btn-warning" data-bs-toggle="modal" data-bs-target="#updateData"
                    data-name="' . $row->name . '"
                    data-url="' . route('jenis-non-perum.update', ['id' => $id]) . '"
                    >
                        Edit
                    </button>';
                    $btn .= '
                    <form id="deleteForm" action="' . route('jenis-non-perum.destroy', ['id' => $id]) . '" method="POST">
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
        return view('master.jenis-non-perum');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        \DB::table('master_jenis_non_perum')->insert([
            'name' => $request->name,
        ]);
        return redirect()->back()->with(['message' => 'Jenis Non Perum berhasil ditambahkan', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'name' => 'required',
        ]);
        \DB::table('master_jenis_non_perum')->where('id', $id)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('jenis-non-perum.index')->with(['message' => 'Jenis Non Perum berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        \DB::table('master_jenis_non_perum')->where('id', $id)->delete();
        return redirect()->route('jenis-non-perum.index')->with(['message' => 'Jenis Non Perum berhasil di delete', 'status' => 'success']);
    }
}
