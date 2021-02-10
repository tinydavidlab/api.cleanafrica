<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Customer;
use App\Models\Trip;
use App\Utilities\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\Response;


class CSVImporter extends Controller
{
    //

    public function import($type): \Illuminate\Http\JsonResponse
    {
        $type = strtolower($type);
        $key = request('company_id');

        if (method_exists($this, $type)) {
            return $this->$type($type, $key);
        }

        return response()->json(['message' => 'Method not found'], Response::HTTP_FORBIDDEN);
    }

    public function company($type, $key): \Illuminate\Http\JsonResponse
    {
        if (request()->hasFile('csv')) {
            $csv = request()->file( 'csv' );
            $csv = Reader::createFromPath($csv, 'r');
            $csv->setHeaderOffset( 0 );

            foreach ( $csv->getRecords() as $record ) {
                Trip::create([
                    'company_id' => $key,
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
                    //'delivery_status' => $record[ 'Status' ],
                    'notes' => $record[ 'Notes' ],
                    'customer_latitude' => $record[ 'Customer Latitude' ],
                    'customer_longitude' => $record[ 'Customer Longitude' ],
                ]);
            }

        }
        return response()->json([
            'message' => 'File uploaded successfully...'
        ], Response::HTTP_OK);
    }

    public function customer($type, $key)
    {
        if (request()->hasFile('csv')) {
            $csv = request()->file( 'csv' );
            $csv = Reader::createFromPath($csv, 'r');
            $csv->setHeaderOffset( 0 );

            foreach ( $csv->getRecords() as $record ) {
                Customer::create([
                    'company_id' => $key,
                    'name' => $record[ 'Customer Name' ],
                    'phone_number' => $record[ 'Phone Number' ],
                    'address' => $record[ 'Road Name' ],
                    'country' => $record[ 'Country' ],
                    'division' => $record[ 'Division' ],
                    'subdivision' => $record[ 'Sub Division' ],
                    'snoocode' => $record[ 'Snoocode' ],
                    'latitude' => $record[ 'Latitude' ],
                    'longitude' => $record[ 'Longitude' ],
                    'password' => Hash::make( $record[ 'Phone Number' ] ),
                ]);
            }

        }
        return response()->json([
            'message' => 'File uploaded successfully...'
        ], Response::HTTP_OK);
    }

    public function agent($type, $key)
    {
        if (request()->hasFile('csv')) {
            $csv = request()->file( 'csv' );
            $csv = Reader::createFromPath($csv, 'r');
            $csv->setHeaderOffset( 0 );

            foreach ( $csv->getRecords() as $record ) {
                Agent::create([
                    'company_id' => $key,
                    'name' => $record[ 'Name' ],
                    'phone_number' => $record[ 'Phone Number' ],
                    'type' => $record[ 'Role' ],
                    'password' => Hash::make( $record[ 'Phone Number' ] ),
                ]);
            }
        }
        return response()->json([
            'message' => 'File uploaded successfully...'
        ], Response::HTTP_OK);
    }
    public function admin($type, $key)
    {
        if (request()->hasFile('csv')) {
            $csv = request()->file( 'csv' );
            $csv = Reader::createFromPath($csv, 'r');
            $csv->setHeaderOffset( 0 );

            foreach ( $csv->getRecords() as $record ) {
                Admin::create([
                    'company_id' => $key,
                    'name' => $record[ 'Name' ],
                    'phone_number' => $record[ 'Phone Number' ],
                    'type' => $record[ 'Role' ],
                    'password' => Hash::make( $record[ 'Phone Number' ] ),
                ]);
            }
        }
        return response()->json([
            'message' => 'File uploaded successfully...'
        ], Response::HTTP_OK);
    }
}
