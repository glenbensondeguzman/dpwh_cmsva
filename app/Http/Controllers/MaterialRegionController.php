<?php

namespace App\Http\Controllers;
use App\Models\MaterialSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialRegionController extends Controller
{
        public function updateValidation(Request $request, $id)
        {
            $request->validate([
                'status' => 'required|in:passed,failed',
                'reason_status' => 'nullable|string'
            ]);

            $material = MaterialSource::findOrFail($id);
            $material->status = $request->status;
            $material->reason_status = $request->status === 'failed' ? $request->reason : null;
            $material->user_id_validation = Auth()->user()->role_id;
            $material->save();

            return response()->json(['message' => 'Material status and user updated successfully.']);
        }
}
