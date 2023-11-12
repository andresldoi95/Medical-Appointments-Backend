<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\General\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientApiController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'page' => 'required|integer',
            'per_page' => 'required|integer',
            'sort_by' => 'required|string',
            'sort_order' => 'required|in:asc,desc',
            'team_id' => 'required|integer|exists:teams,id',
            'search' => 'nullable'
        ]);
        $search = $request->input('search');
        return Patient::withTrashed()
            ->where('team_id',  $request->input('team_id'))
            ->orderBy($request->input('sort_by'), $request->input('sort_order'))
            ->where(function ($query) use($search) {
                if ($search)
                    $query->where('last_name', 'like', "%$search%");
            })
            ->paginate($request->input('per_page', ['*'], 'page', $request->input('page')));
    }
    private function getValidationRules(Request $request, $id = null) {
        return [
            'team_id' => 'required|integer|exists:teams,id',
            'identification' => [
                'required', 'max:20', Rule::unique('patients')
                    ->where('team_id', $request->input('team_id'))
                    ->ignore($id)
            ],
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birth_day' => 'required|date',
            'address' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|max:255|email',
            'city' => 'required|max:255'
        ];
    }
    private function createOrUpdatePatient(Request $request) {
        return Patient::updateOrCreate([
            'team_id' => $request->input('team_id'),
            'identification' => $request->input('identification')
        ], [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'birth_day' => new Carbon($request->input('birth_day')),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'city' => $request->input('city')
        ]);
    }
    public function store(Request $request) {
        $request->validate($this->getValidationRules($request));
        return $this->createOrUpdatePatient($request);
    }
    public function update(Request $request, $id) {
        $request->validate($this->getValidationRules($request, $id));
        return $this->createOrUpdatePatient($request);
    }
    public function destroy(Request $request, $id) {
        $patient = Patient::withTrashed()->findOrFail($id);
        if ($patient->trashed())
            $patient->restore();
        else
            $patient->delete();
        return $patient;
    }
}
