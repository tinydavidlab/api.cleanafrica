<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Repositories\TripRepository;
use Illuminate\Http\Client\Request;

class ProcessImageUpload extends Job
{
    /**
     * @var Trip
     */
    public $model;
    /**
     * @var Request
     */
    public $request;
    /**
     * @var string
     */
    public $name;
    /**
     * @var TripRepository
     */
    private $repository;

    /**
     * Create a new job instance.
     * @param string $filename
     * @param string $folder
     */
    public function __construct( string $filename, string $folder )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: Handle image upload.


    }
}
