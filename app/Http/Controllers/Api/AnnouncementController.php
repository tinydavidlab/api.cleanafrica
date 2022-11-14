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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AnnouncementController extends Controller
{
    /**
     * @var AnnouncementRepository
     */
    private $announcementRepository;

    /**
     * AnnouncementController constructor.
     * @param AnnouncementRepository $announcementRepository
     */
    public function __construct(AnnouncementRepository $announcementRepository)
    {
        $this->announcementRepository = $announcementRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $id)
    {
        $announcements = $this->announcementRepository
            ->scopeQuery( function ( $query ) {
                return $query->orderBy('created_at', 'desc');
            })
            ->getForCompany($id);

        $announcements = fractal($announcements, new AnnouncementTransformer())
        ->withResourceName('announcements')
        ->toArray();

        return response()->json(['announcements' => $announcements], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'photo' => 'image',
            'title' => 'required',
            'content' => 'required'
        ]);

        $content = strip_tags($request->get('content'));

        $announcement = $this->announcementRepository->create(array_merge($request->except('photo', 'content'), ['content' => $content]));

        if ($request->hasFile('photo')) {
            $filename = ImageUploader::upload($request->file('photo'));
            $this->dispatch(new ProcessImageUpload($filename, 'announcements'));
            $this->announcementRepository->update(['photo' => $filename], $announcement->id);
        }

        event(new SendAnnouncementToCollector($announcement));

        $announcement = fractal($announcement->fresh(), new AnnouncementTransformer())
        ->withResourceName('announcements')
        ->toArray();

        return response()->json(['announcement' => $announcement], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $announcement = $this->announcementRepository->find($id);
            $announcement = fractal($announcement, new AnnouncementTransformer())
                ->withResourceName('announcements')
                ->toArray();

            return response()->json(['announcement' => $announcement], Response::HTTP_CREATED);
        } catch (ModelNotFoundException $exception) {
            return response()->json( [ 'message' => 'No announcement was found with id: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $announcement = $this->announcementRepository->update( $request->except( [ 'photo' ] ), $id );
        $announcement = fractal( $announcement, new AnnouncementTransformer() )
            ->withResourceName( 'announcements' )
            ->toArray();

        return response()->json( [ 'announcement' => $announcement ], Response::HTTP_OK );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->announcementRepository->delete( $id );
            return response()->json( [], Response::HTTP_NO_CONTENT );
        } catch ( ModelNotFoundException $exception ) {
            return response()->json(
                [ 'message' => 'No announcement was found with: ' . $id ], Response::HTTP_NOT_FOUND );
        }
    }
}
