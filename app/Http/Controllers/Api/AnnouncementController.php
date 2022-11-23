<?php

namespace App\Http\Controllers\Api;

use App\Events\SendAnnouncementToCollector;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageUpload;
use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use App\Transformers\AnnouncementTransformer;
use App\Utilities\ImageUploader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AnnouncementController extends Controller
{
    /**
     * AnnouncementController constructor.
     *
     * @param AnnouncementRepository $announcementRepository
     */
    public function __construct( public AnnouncementRepository $announcementRepository )
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( int $id ): JsonResponse
    {
        $announcements = $this->announcementRepository
            ->scopeQuery( function ( $query ) {
                return $query->orderBy( 'created_at', 'desc' );
            } )
            ->getForCompany( $id );

        $announcements = fractal( $announcements, new AnnouncementTransformer() )
            ->withResourceName( 'announcements' )
            ->toArray();

        return response()->json( [ 'announcements' => $announcements ], ResponseAlias::HTTP_OK );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(): JsonResponse
    {
        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Illuminate\Validation\ValidationException
     */
    public function store( Request $request ): JsonResponse
    {
        $this->validate( $request, [
            'photo'   => 'image',
            'title'   => 'required',
            'content' => 'required',
        ] );

        $content = strip_tags( $request->get( 'content' ) );

//        dd( $content );

        $announcement = $this->announcementRepository->create( array_merge( $request->except( 'photo', 'content' ), [ 'content' => $content ] ) );

        if ( $request->hasFile( 'photo' ) ) {
            $filename = ImageUploader::upload( $request->file( 'photo' ) );
            $this->dispatch( new ProcessImageUpload( $filename, 'announcements' ) );
            $this->announcementRepository->update( [ 'photo' => $filename ], $announcement->id );
        }

        event( new SendAnnouncementToCollector( $announcement ) );

        $announcement = fractal( $announcement->fresh(), new AnnouncementTransformer() )
            ->withResourceName( 'announcements' )
            ->toArray();

        return response()->json( [ 'announcement' => $announcement ], ResponseAlias::HTTP_CREATED );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( int $id ): JsonResponse
    {
        try {
            $announcement = $this->announcementRepository->find( $id );
            $announcement = fractal( $announcement, new AnnouncementTransformer() )
                ->withResourceName( 'announcements' )
                ->toArray();

            return response()->json( [ 'announcement' => $announcement ], ResponseAlias::HTTP_CREATED );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json( [ 'message' => 'No announcement was found with id: ' . $id ], ResponseAlias::HTTP_NOT_FOUND );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Announcement $announcement
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( Announcement $announcement ): JsonResponse
    {
        return response()->json();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update( Request $request, int $id ): JsonResponse
    {
        $announcement = $this->announcementRepository->update( $request->except( [ 'photo' ] ), $id );
        $announcement = fractal( $announcement, new AnnouncementTransformer() )
            ->withResourceName( 'announcements' )
            ->toArray();

        return response()->json( [ 'announcement' => $announcement ], ResponseAlias::HTTP_OK );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( int $id ): JsonResponse
    {
        try {
            $this->announcementRepository->delete( $id );
            return response()->json( [], ResponseAlias::HTTP_NO_CONTENT );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No announcement was found with: ' . $id ], ResponseAlias::HTTP_NOT_FOUND );
        }
    }
}
