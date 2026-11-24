<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vehicle;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends BaseApiController
{
    /**
     * List all vehicles belonging to member
     */
    public function index(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $vehicles = Vehicle::where('member_id', $member->id)->latest()->get();
        return $this->successResponse($vehicles, 'Orodha ya vyombo vya moto vya umeme.');
    }

    /**
     * Store new EV
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string', // Bajaji, Bodaboda, Car, Bus, Van, Truck
            'make' => 'required|string',
            'model' => 'required|string',
            'registration_number' => 'required|string|max:20',
            'ownership_type' => 'required|string', // Owned, Leased, Financed, Driver-Operated
            'battery_capacity_kwh' => 'nullable|numeric',
            'charging_type' => 'nullable|string', // Swap, AC Slow, DC Fast
            'daily_average_km' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Taarifa za chombo hazijakamilika.', 422, $validator->errors());
        }

        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $vehicle = Vehicle::create([
            'member_id' => $member->id,
            'vehicle_type' => $request->input('vehicle_type'),
            'make' => $request->input('make'),
            'model' => $request->input('model'),
            'registration_number' => strtoupper(trim($request->input('registration_number'))),
            'year_of_manufacture' => $request->input('year_of_manufacture', date('Y')),
            'ownership_type' => $request->input('ownership_type'),
            'battery_capacity_kwh' => $request->input('battery_capacity_kwh', 10.0),
            'charging_type' => $request->input('charging_type', 'Battery Swap'),
            'daily_average_km' => $request->input('daily_average_km', 80),
        ]);

        return $this->successResponse($vehicle, 'Chombo cha moto kimesajiliwa kwa mafanikio.', 201);
    }

    /**
     * Show vehicle detail
     */
    public function show(int $id): JsonResponse
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->errorResponse('Chombo hakikupatikana.', 404);
        }

        return $this->successResponse($vehicle, 'Taarifa za chombo cha umeme.');
    }

    /**
     * Update vehicle
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->errorResponse('Chombo hakikupatikana.', 404);
        }

        $vehicle->update($request->only([
            'make', 'model', 'vehicle_type', 'registration_number',
            'ownership_type', 'battery_capacity_kwh', 'charging_type', 'daily_average_km'
        ]));

        return $this->successResponse($vehicle, 'Taarifa za chombo zimesasishwa.');
    }

    /**
     * Delete vehicle
     */
    public function destroy(int $id): JsonResponse
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->errorResponse('Chombo hakikupatikana.', 404);
        }

        $vehicle->delete();
        return $this->successResponse(null, 'Chombo kimeondolewa kwenye mfumo.');
    }
}
