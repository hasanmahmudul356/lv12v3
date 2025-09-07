<?php

namespace App\Http\Controllers;

use App\Models\EnergyBill;
use App\Models\Meter;
use App\Models\MeterReading;
use function Carbon\map;
use function Illuminate\Cache\table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function getBillingInfo(Request $request)
    {
        $meter_id = $request->meter_id;
        $billing_month = $request->billing_month;

//        ddA($request);
        if (!$meter_id || !$billing_month) {
            return response()->json(['status' => 400, 'message' => 'Meter ID or month missing']);
        }

        $month_start = $billing_month . '-01';
        $month_end = date("Y-m-t", strtotime($month_start));

        $customer = Meter::where('meters.id', $meter_id)
            ->leftJoin('customers','meters.customer_id','=','customers.id')
            ->select('customers.*')
            ->first();



        $energy_bill = EnergyBill::whereIn('type', [$customer->solar, $customer->nesco, $customer->generator])->where('billing_month',$billing_month)
            ->get();


        $energy_bill_calculate = $energy_bill->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'billing_month' => $item->billing_month,
                'customer_unit' => $item->customer_unit,
                'unit_rate' => $item->unit_rate,
                'bill_amount' => $item->customer_unit * $item->unit_rate, // আপনার calculation
            ];
        });


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

        if ($data['end_reading'] !== null) {
            $data['units_consumed'] = $data['end_reading'] - $data['start_reading'];
            $data['bill_amount'] = $data['units_consumed'] * $data['unit_rate'];
        } else {
            $data['units_consumed'] = 0;
            $data['bill_amount'] = 0;
            $data['end_reading'] =0;

        }



        return returnData(2000, $data);
    }
}
