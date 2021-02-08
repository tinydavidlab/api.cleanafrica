<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Utilities\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\Response;


class CSVImporter extends Controller
{
    //

    public function import(int $id, Request $request)
    {
        if ($request->hasFile('csv')) {
            $csv = $request->file( 'csv' );
            $csv = Reader::createFromPath($csv, 'r');
            $csv->setHeaderOffset( 0 );

            Schema::disableForeignKeyConstraints();
            DB::table( 'trips' )->truncate();
            Schema::enableForeignKeyConstraints();

            foreach ( $csv->getRecords() as $record ) {
                Trip::create([
                    'company_id' => $id,
                    'customer_name' => $record[ 'Customer Name' ],
                    'customer_primary_phone_number' => $record[ 'Primary Phone Number' ],
                    'customer_secondary_phone_number' => $record[ 'Secondary Phone Number' ],
                    'customer_apartment_number' => $record[ 'Road Name' ],
                    'customer_country' => $record[ 'Customer Country' ],
                    'customer_division' => $record[ 'Customer Division' ],
                    'customer_subdivision' => $record[ 'Customer Subdivision' ],
                    'customer_snoocode' => $record[ 'Customer Snoocode' ],
                    'collector_date' => $record[ 'Collector Date' ],
                    'collector_time' => $record[ 'Collector Time' ],
                    'bin_liner_quantity' => $record[ 'Bin Liner Quantity' ],
                    'delivery_status' => $record[ 'Status' ],
                    'notes' => $record[ 'Notes' ],
                    'customer_latitude' => $record[ 'Customer Latitude' ],
                    'customer_longitude' => $record[ 'Customer Longitude' ],
                ]);
            }

        }
        return response()->json([
            'message' => 'File uploaded successfully...'. $id
        ], Response::HTTP_OK);
    }
}
