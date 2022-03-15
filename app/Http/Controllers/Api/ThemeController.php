<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    public $esService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(EsService $esService)
    {
        $this->esService = $esService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'title' => 'required',
            'userId' => 'required|numeric',
            'username' => 'required',
            'category' => 'nullable',
            'link' => 'nullable',
            'caption' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        try {
            $this->esService->store($request);
            $response['message'] = 'Saved Successfully';
            return response()->json($response, Response::HTTP_OK, []);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'title' => 'required',
            'userId' => 'nullable',
            'username' => 'nullable',
            'category' => 'required',
            'link' => 'nullable',
            'caption' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        try {
            $this->esService->update($request);
            $response['message'] = 'Updated Successfully';
            return response()->json($response, Response::HTTP_OK, []);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }

    public function search(Request $request)
    {
        $response = array();
        $get_results = $this->esService->search($request->get('keyword'), $request->get('offset'));
        if ($get_results) {
            $status = Response::HTTP_OK;
            $response['status'] = true;
            $response['themes'] = $get_results;
        } else {
            $status = Response::HTTP_NO_CONTENT;
            $response['status'] = false;
            $response['message'] = 'No themes found!';
        }
        return response()->json($response, $status);
    }

    public function delete(Request $request)
    {
        try {
            $this->esService->delete($request->get('id'));
            $response['message'] = 'Deleted Successfully';
            return response()->json($response, Response::HTTP_OK, []);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_themes(Request $request)
    {

        try {
            $this->esService->store($request);
            $response['message'] = 'Saved Successfully';
            return response()->json($response, Response::HTTP_OK, []);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }
}
