<?php

namespace App\Http\Controllers;

use App\Models\MaterialSource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{



public function showMap(Request $request)
{
    // Get selected region from dropdown
    $selectedRegion = $request->input('region');

    // For dropdown: get all unique regions
    $regions = MaterialSource::select('region')->distinct()->pluck('region');

    // Main map data (filtered if region is selected)
    $locations = MaterialSource::when($selectedRegion, function ($query) use ($selectedRegion) {
        return $query->where('region', $selectedRegion);
    })->get();

    // Total number of approved materials (based on filtered results)
    $totalMaterials = $locations->count();

    // --- Central Office: For Approval ---
    $centralForApprovalLists = MaterialSource::where('user_id_validation', 2)
        ->when($selectedRegion, function ($query) use ($selectedRegion) {
            return $query->where('region', $selectedRegion);
        })
        ->get();

    $centralForApproval = $centralForApprovalLists->count();

    // --- Regional Office: For Approval ---
    $regionalForApprovalLists = MaterialSource::where('user_id_validation', 3)
        ->when($selectedRegion, function ($query) use ($selectedRegion) {
            return $query->where('region', $selectedRegion);
        })
        ->get();

    $regionalForApproval = $regionalForApprovalLists->count();

    // Pass everything to Blade view
    return view('welcome', compact(
        'locations',
        'regions',
        'selectedRegion',
        'totalMaterials',
        'centralForApproval',
        'regionalForApproval',
        'centralForApprovalLists',
        'regionalForApprovalLists'
    ));
}



public function index()
{
    // Redirect to home (or login) if not authenticated
    if (!Auth::check()) {
        return view('welcome');
    }
    $role_id = Auth::user()->role_id;
    if ($role_id == '1') {
        $materials = MaterialSource::orderBy('created_at', 'asc')
            ->where('user_id_validation', 2)
            ->where('status', 'Approved')
            ->get();
            $totalMaterials = $materials->count();
        return view('central.central_main', compact('materials', 'totalMaterials'));
    } elseif ($role_id == '2') {
       $materials = MaterialSource::where('region', Auth::user()->region)
            ->where('user_id_validation', 3)
            ->whereNull('status')
            ->orderBy('created_at', 'asc')
            ->get();
        $totalMaterials = $materials->count();
        return view('region.region_main', compact('materials', 'totalMaterials'));
    } elseif ($role_id == '3') {
    $materials = MaterialSource::where('user_id', Auth::id())->orderBy('created_at', 'asc')->get();
    $totalMaterials = $materials->count();
        return view('district.district_main', compact('materials', 'totalMaterials'));
    } 
}


        /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $m_source=MaterialSource::findOrFail($id);

        if($m_source->photo && Storage::exists('public/uploads/'.$m_source->photo)){
            Storage::delete('public/uploads/'.$m_source->photo);
        }
        //$user->restore();
        $m_source->delete();
        return response()->json(['success'=>'User deleted successfully']);
    }

    
            public function updateValidation(Request $request, $id)
        {
            $request->validate([
                'status' => 'required|in:Approved,Disapproved',
                'reason_status' => 'nullable|string'
            ]);

            $material = MaterialSource::findOrFail($id);
            $material->status = $request->status;
            $material->reason_status = $request->status === 'failed' ? $request->reason : null;
            $material->user_id_validation = Auth()->user()->role_id;
            $material->save();

            return response()->json(['message' => 'Material status and user updated successfully.']);
        }


// gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg


    public function edit($id)
    {
         $materialSource=MaterialSource::findOrFail($id);
         return view('cmsva_form.form_edit', compact('materialSource'));
    }


        public function update(Request $request, $id)
{
    $materialSource = MaterialSource::findOrFail($id);

    $validated = $request->validate([
        'material_source_name' => 'required|string|max:255',
        'access_road' => 'required|string',
        'directional_flow' => 'required|string',
        'source_type' => 'required|string',
        'potential_uses' => 'required|string',
        'future_use_recommendation' => 'nullable|string',
        'province' => 'required|string',
        'municipality' => 'required|string',
        'barangay' => 'required|string',
        'renewability' => 'required|in:yes,no',
        'remarks_renewable' => 'nullable|string',
        'remarks_nonrenewable' => 'nullable|string',
        'nonrenewable_reason' => 'nullable|string',
        'processing_plant_info' => 'nullable|string',
        'observations' => 'nullable|string',
        'permittee_name' => 'nullable|string',
        'quarry_permit_date' => 'nullable|date',
        'quality_test_date' => 'nullable|date',
        'quality_test_result' => 'nullable|in:Passed,Failed',
        'quarry_permit' => 'nullable|file|mimes:pdf,jpg,png,docx',
        'quality_test_attachment' => 'nullable|file|mimes:pdf,jpg,png,docx',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $quarryPermitFile = $materialSource->quarry_permit;
    $qualityTestFile = $materialSource->quality_test_attachment;

    // Handle quarry permit file
    if ($request->hasFile('quarry_permit')) {
        if ($materialSource->quarry_permit && Storage::exists('public/uploads/'.$materialSource->quarry_permit)) {
            Storage::delete('public/uploads/'.$materialSource->quarry_permit);
        }
        $file = $request->file('quarry_permit');
        $quarryPermitFile = time().'_quarry_'.$file->getClientOriginalName();
        $file->storeAs('public/uploads/', $quarryPermitFile);
    }

    // Handle quality test file
    if ($request->hasFile('quality_test_attachment')) {
        if ($materialSource->quality_test_attachment && Storage::exists('public/uploads/'.$materialSource->quality_test_attachment)) {
            Storage::delete('public/uploads/'.$materialSource->quality_test_attachment);
        }
        $file = $request->file('quality_test_attachment');
        $qualityTestFile = time().'_test_'.$file->getClientOriginalName();
        $file->storeAs('public/uploads/', $qualityTestFile);
    }

    $materialSource->update([
        'material_source_name' => $validated['material_source_name'],
        'access_road' => $validated['access_road'],
        'directional_flow' => $validated['directional_flow'],
        'source_type' => $validated['source_type'],
        'potential_uses' => $validated['potential_uses'],
        'future_use_recommendation' => $validated['future_use_recommendation'] ?? null,
        'province' => $validated['province'],
        'municipality' => $validated['municipality'],
        'barangay' => $validated['barangay'],
        'renewability' => $validated['renewability'],
        'remarks_renewable' => $validated['remarks_renewable'] ?? null,
        'remarks_nonrenewable' => $validated['remarks_nonrenewable'] ?? null,
        'nonrenewable_reason' => $validated['nonrenewable_reason'] ?? null,
        'processing_plant_info' => $validated['processing_plant_info'] ?? null,
        'observations' => $validated['observations'] ?? null,
        'permittee_name' => $validated['permittee_name'] ?? null,
        'quarry_permit_date' => $validated['quarry_permit_date'] ?? null,
        'quality_test_date' => $validated['quality_test_date'] ?? null,
        'quality_test_result' => $validated['quality_test_result'] ?? null,
        'quarry_permit' => $quarryPermitFile,
        'quality_test_attachment' => $qualityTestFile,
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],
    ]);

    return redirect()->route('dashboard')->with('success', 'Material Source updated successfully.');
}


// ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg


    public function create()
    {
        return  view('cmsva_form.form_add');
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'material_source_name' => 'required|string|max:255',
        'access_road' => 'required|string',
        'directional_flow' => 'required|string',
        'source_type' => 'required|string',
        'potential_uses' => 'required|string',
        'future_use_recommendation' => 'nullable|string',
        'province' => 'required|string',
        'municipality' => 'required|string',
        'barangay' => 'required|string',
        'renewability' => 'required|in:yes,no',
        'processing_plant_info' => 'nullable|string',
        'observations' => 'nullable|string',
        'permittee_name' => 'nullable|string',
        'quarry_permit_date' => 'nullable|date',
        'quality_test_date' => 'nullable|date',
        'quality_test_result' => 'nullable|in:Passed,Failed',
        'quarry_permit' => 'nullable|file|mimes:pdf,jpg,png,docx',
        'quality_test_attachment' => 'nullable|file|mimes:pdf,docx',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $quarryPermitFile = null;
    $qualityTestFile = null;

    // Handle quarry permit file
    if ($request->hasFile('quarry_permit')) {
        $file = $request->file('quarry_permit');
        $quarryPermitFile = time().'_quarry_'.$file->getClientOriginalName();
        $file->storeAs('public/uploads/', $quarryPermitFile);
    }

    // Handle quality test file
    if ($request->hasFile('quality_test_attachment')) {
        $file = $request->file('quality_test_attachment');
        $qualityTestFile = time().'_test_'.$file->getClientOriginalName();
        $file->storeAs('public/uploads/', $qualityTestFile);
    }

    // Create new Material Source
    MaterialSource::create([
        'material_source_name' => $validated['material_source_name'],
        'access_road' => $validated['access_road'],
        'directional_flow' => $validated['directional_flow'],
        'source_type' => $validated['source_type'],
        'potential_uses' => $validated['potential_uses'],
        'future_use_recommendation' => $validated['future_use_recommendation'] ?? null,
        'province' => $validated['province'],
        'municipality' => $validated['municipality'],
        'barangay' => $validated['barangay'],
        'renewability' => $validated['renewability'],
        'processing_plant_info' => $validated['processing_plant_info'] ?? null,
        'observations' => $validated['observations'] ?? null,
        'permittee_name' => $validated['permittee_name'] ?? null,
        'quarry_permit_date' => $validated['quarry_permit_date'] ?? null,
        'quality_test_date' => $validated['quality_test_date'] ?? null,
        'quality_test_result' => $validated['quality_test_result'] ?? null,
        'quarry_permit' => $quarryPermitFile,
        'quality_test_attachment' => $qualityTestFile,
        'prepared_by' => Auth()->user()->name,
        'user_id' => Auth()->user()->id,
        'user_id_validation' => Auth()->user()->role_id,
        'region' => Auth()->user()->region,
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],

    ]);

    return redirect()->route('dashboard')->with('success', 'Material Source created successfully.');
}




    public function restoreSourceView()
    {
           // Redirect to home (or login) if not authenticated
    if (!Auth::check()) {
        return view('welcome');
    }
    $role_id = Auth::user()->role_id;
    if ($role_id == '1') {
        $materials = MaterialSource::onlyTrashed()
            ->orderBy('created_at', 'asc')
            ->get();
        $totalMaterials = $materials->count();
        return view('cmsva_form.form_archived', compact('materials', 'totalMaterials'));
    } elseif ($role_id == '2') {
       $materials = MaterialSource::onlyTrashed()
            ->where('region', Auth::user()->region)
            ->where('user_id_validation', 3)
            ->whereNull('status')
            ->orderBy('created_at', 'asc')
            ->get();
        $totalMaterials = $materials->count();
        return view('cmsva_form.form_archived', compact('materials', 'totalMaterials'));
    } elseif ($role_id == '3') {
    $materials = MaterialSource::onlyTrashed()
        ->orderBy('created_at', 'asc')
        ->get();
    
    $totalMaterials = $materials->count();
        return view('cmsva_form.form_archived', compact('materials', 'totalMaterials'));
    } 
       
    }



    public function restoreSource($id)
    {
        $m_source=MaterialSource::onlyTrashed()->findOrFail($id);
        $m_source->restore();
        return response()->json(['success'=>'Material Source restored successfully']);
    }


// aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa





}