<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use function Illuminate\Cache\table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function getBillingInfo(Request $request)
    {
        $meter_id = $request->meter_id;
        $billing_month = $request->billing_month;

        if (!$meter_id || !$billing_month) {
            return response()->json(['status' => 400, 'message' => 'Meter ID or month missing']);
        }

        $month_start = $billing_month . '-01';
        $month_end = date("Y-m-t", strtotime($month_start));

        $last_entry = MeterReading::where('meter_no', $meter_id)
            ->where('reading_date', '<', $month_start)
            ->orderBy('reading_date', 'desc')
            ->first();

        $data['start_reading'] = $last_entry ? $last_entry->current_reading : 0;

        $current_entry = MeterReading::where('meter_no', $meter_id)
            ->whereBetween('reading_date', [$month_start, $month_end])
            ->orderBy('reading_date', 'desc')
            ->first();

        $data['unit_rate'] = DB::table('meters')
            ->where('meters.id', $meter_id)
            ->leftJoin('tariff_and_rates', 'meters.meter_type', '=', 'tariff_and_rates.meter_type')
            ->select('tariff_and_rates.unit_rate')
            ->value('unit_rate');


        $data['end_reading'] = $current_entry ? $current_entry->current_reading : null;

        return returnData(2000, $data);


    }
}
