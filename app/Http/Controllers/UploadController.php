<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        /* Validate */
        $request->validate([
            'uploadfile'    => 'mimes:csv,txt|required'
        ]);

        /* UPloading */
        $saveFile   = $request->uploadfile->store('storage/uploads', 'public');

        /* Failed upload */
        if (!$saveFile) {
            return response()->json([
                'success'   => false,
                'info'      => "Failed uploading data",
                'errors'    => [
                    ["Failed uploading data"]
                ]
            ], 422);
        }


        $upload     = Upload::create([
            'filename'  => $saveFile,
            'status'    => "0",
        ]);

        if (!$upload) {
            return response()->json([
                'success'   => false,
                'info'      => "Failed saving data",
                'errors'    => [
                    ["Failed saving data"]
                ]
            ], 422);
        }

        return response()->json([
            'success'   => true,
            'info'      => "Success uploading data"
        ]);
    }
}
