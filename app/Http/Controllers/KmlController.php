<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KmlController extends Controller
{
    public function index()
    {
        return view('kml-integration');
    }

    public function action(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'select_file' => 'required'
        ]);
        if($validation->passes())
        {
            $kml= $request->file('select_file');
            $new_name = rand() . '.' . $kml->getClientOriginalExtension();
            $kml->move(public_path('kml'), $new_name);
            return response()->json([
                'message'   => 'File Uploaded Successfully',
                'uploaded_file' => "kml/$new_name",
                'class_name'  => 'alert-success',
            ]);
        }
        else
        {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_file' => '',
                'class_name'  => 'alert-danger',
            ]);
        }
    }
}
