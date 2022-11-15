<?php

namespace App\Transformers;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class AnnouncementTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes
        = [

        ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes
        = [
            'company',
        ];

    /**
     * A Fractal transformer.
     *
     * @param Announcement $announcement
     *
     * @return array
     */
    public function transform( Announcement $announcement )
    {
        return [
            'id'           => $announcement->getAttribute( 'id' ),
            'company_id'   => $announcement->getAttribute( 'company_id' ),
            'company_name' => $announcement->company->name ?? null,
            'title'        => $announcement->getAttribute( 'title' ),
            'content'      => $announcement->getAttribute( 'content' ),
            'type'         => $announcement->getAttribute( 'type' ),
            'photo'        => $this->getImageUrl( $announcement ),
            'priority'     => $announcement->getAttribute( 'priority' ),
            'created_at'   => Carbon::parse( $announcement->getAttribute( 'created_at' ) )->format( 'l, d F Y @ H:i:s' ),

        ];
    }

    public function getImageUrl( Announcement $announcement )
    {
        if ( $announcement->getAttribute( 'photo' ) == null ) {
            return null;
        }

        return Storage::disk( 's3' )->url( 'announcements/' . $announcement->getAttribute( 'photo' ) );
    }

    public function includeCompany( Announcement $announcement )
    {
        if ( !$announcement->company ) return null;

        return $this->item( $announcement->company, new CompanyTransformer(), 'companies' );
    }
}
