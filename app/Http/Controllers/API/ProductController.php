<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
// use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::all();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($request->all());

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->update($request->all());

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}