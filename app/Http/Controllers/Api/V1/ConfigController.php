<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\MembershipCategory;
use App\Models\Fee;
use App\Models\Region;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigController extends BaseApiController
{
    /**
     * Get authoritative fee structure
     */
    public function fees(): JsonResponse
    {
        $categories = MembershipCategory::select([
            'id', 'name', 'slug', 'description', 'eligibility_criteria', 'required_documents', 'registration_fee', 'annual_fee', 'fee_status_note'
        ])->get();

        $configuredFees = Fee::where('is_active', true)->get();

        return $this->successResponse([
            'currency' => 'TZS',
            'fee_policy_note' => 'Official fees are subject to TEVDA National Council confirmation and gazetting. Please verify active invoice amounts.',
            'categories' => $categories,
            'official_fees' => $configuredFees,
            'payment_channels' => [
                [
                    'code' => 'gepg',
                    'name' => 'Government Electronic Payment Gateway (GePG)',
                    'instruction' => 'Lipa kupitia Benki yoyote au Namba ya Malipo (Control Number).'
                ],
                [
                    'code' => 'mpesa',
                    'name' => 'Vodacom M-Pesa Lipa Namba',
                    'paybill' => '500200',
                    'instruction' => 'Piga *150*00# -> Lipa kwa M-Pesa -> Weka Namba ya Kampuni au Control Number.'
                ],
                [
                    'code' => 'tigopesa',
                    'name' => 'Tigo Pesa / Airtel Money / HaloPesa',
                    'instruction' => 'Lipa kwa namba ya udhibiti (Control Number).'
                ],
                [
                    'code' => 'crdb',
                    'name' => 'CRDB Bank Plc',
                    'account_name' => 'Tanzania Electric Vehicle Drivers Association',
                    'account_number' => '0150829104000',
                    'branch' => 'Tower Branch, Dar es Salaam'
                ],
                [
                    'code' => 'nmb',
                    'name' => 'NMB Bank Plc',
                    'account_name' => 'Tanzania Electric Vehicle Drivers Association',
                    'account_number' => '20110048291',
                    'branch' => 'Clock Tower, Dar es Salaam'
                ]
            ]
        ], 'Ada na njia rasmi za malipo zilizoidhinishwa.');
    }

    /**
     * Get Official Membership Categories & Requirements
     */
    public function categories(): JsonResponse
    {
        $categories = MembershipCategory::all();
        return $this->successResponse($categories, 'Makundi rasmi ya wanachama TEVDA.');
    }

    /**
     * Get Tanzania Regions
     */
    public function regions(): JsonResponse
    {
        $regions = Region::select('id', 'name', 'code')->orderBy('name')->get();
        return $this->successResponse($regions, 'Mikoa ya Tanzania.');
    }

    /**
     * Get Districts by Region
     */
    public function districts(int $regionId): JsonResponse
    {
        $districts = District::where('region_id', $regionId)->select('id', 'region_id', 'name')->orderBy('name')->get();
        return $this->successResponse($districts, 'Wilaya.');
    }

    /**
     * Get Wards by District
     */
    public function wards(int $districtId): JsonResponse
    {
        $wards = Ward::where('district_id', $districtId)->select('id', 'district_id', 'name')->orderBy('name')->get();
        return $this->successResponse($wards, 'Kata.');
    }
}
