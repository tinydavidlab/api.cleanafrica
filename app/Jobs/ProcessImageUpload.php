<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;

class ProcessImageUpload extends Job
{
    private $filename;
    /**
     * @var string
     */
    private $folder;

    /**
     * Create a new job instance.
     * @param string $filename
     * @param string $folder
     */
    public function __construct( string $filename, string $folder )
    {
        $this->filename = $filename;
        $this->folder   = $folder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file     = storage_path( 'uploads/' . $this->filename );
        $uploaded = Storage::disk( 's3' )->put( "{$this->folder}/{$this->filename}", fopen( $file, 'rb+' ), 'public' );

        if ( $uploaded ) unlink( $file );
    }
}
