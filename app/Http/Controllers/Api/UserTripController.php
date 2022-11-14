<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\CreateTripRequest;
    use App\Jobs\ProcessImageUpload;
    use App\Repositories\TripRepository;
    use App\Transformers\TripTransformer;
    use App\Utilities\ImageUploader;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Arr;
    use Prettus\Validator\Exceptions\ValidatorException;
    use Symfony\Component\HttpFoundation\Response;

    class UserTripController extends Controller
    {
        /**
         * @var TripRepository
         */
        private $tripRepository;

        /**
         * UserTripController constructor.
         * @param TripRepository $repository
         */
        public function __construct( TripRepository $repository ) { $this->tripRepository = $repository; }

        /**
         * Display a listing of the resource.
         *
         * @return JsonResponse
         */
        public function index(): JsonResponse
        {
            $trips = $this->tripRepository->getAllTripsForUser();
            $trips = fractal( $trips, new TripTransformer() )
                ->withResourceName( 'trips' )
                ->toArray();

            return response()->json( [ 'trips' => $trips ], Response::HTTP_OK );
        }

        /**
         * Create user trip.
         *
         * @param CreateTripRequest $request
         * @return JsonResponse
         * @throws ValidatorException
         */
        public function store( CreateTripRequest $request ): JsonResponse
        {
            $trip = $this->tripRepository->create(
                Arr::add(
                    $request->except( [ 'bin_image', 'property_image' ] ),
                    'customer_id', auth()->id()
                )
            );

            if ( $request->hasFile( 'bin_image' ) ) {
                $filename = ImageUploader::upload( $request->file( 'bin_image' ) );
                $this->dispatch( new ProcessImageUpload( $filename, 'bins' ) );
                $this->tripRepository->update( [ 'bin_image' => $filename ], $trip->id );
            }

            if ( $request->hasFile( 'property_image' ) ) {
                $filename = ImageUploader::upload( $request->file( 'property_image' ) );
                $this->dispatch( new ProcessImageUpload( $filename, 'properties' ) );
                $this->tripRepository->update( [ 'property_image' => $filename ], $trip->id );
            }

            $trip = fractal( $trip->fresh(), new TripTransformer() )
                ->withResourceName( 'trips' )
                ->toArray();

            return response()->json( [ 'trip' => $trip ], Response::HTTP_CREATED );
        }
    }
