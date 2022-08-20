<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $datas = Data::all();
        return view('data', [
            'datas' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $data = new Data();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();

            $filename = time() . '.' . $extention;

            $file->move('uploaded_files/', $filename);

            $data->image = $filename;
        }

        $image = file_get_contents('uploaded_files/' . $filename);

        // image string data into base64 
        $encdata = base64_encode($image);

        $data_sha256 =  hash('sha256', $encdata);

        $data->image_binary = $encdata;
        $data->image_hash = $data_sha256;

        $data->save();

        return redirect()->back();
    }

    public function verify(Data $data)
    {
        $image = file_get_contents('uploaded_files/' . $data->image);

        // image string data into base64 
        $encdata = base64_encode($image);

        $data_sha256 =  hash('sha256', $encdata);

        if ($data_sha256 == $data->image_hash) {
            $status =  "true";
        } else {
            $status =  "false";

            // script write access.
            $newImagePath = "resotred_files/";
            // $newImageName = $newImagePath . "decoded_image." . $ext;
            $newImageName = $newImagePath . $data->image;

            // Saving the decoded file with the new file name.
            file_put_contents($newImageName, base64_decode($data->image_binary));
        }

        return view('verify', [
            'data' => $data,
            'status' => $status
        ]);
    }
}
