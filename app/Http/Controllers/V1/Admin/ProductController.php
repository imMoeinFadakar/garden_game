<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Products\StoreProductRequest;
use App\Http\Requests\V1\Admin\Products\UpdateProductRequest;
use App\Http\Resources\V1\Admin\ProductResource;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends BaseAdminController{
    
    /**
     * product/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $product = Products::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->farm_id),fn($query) =>  $query->where("farm_id", $request->farm_id))
        ->when(isset($request->min_token_value),fn($query) =>  $query->where("min_token_value", $request->min_token_value))
        ->when(isset($request->max_token_value	),fn($query) =>  $query->where("max_token_value", $request->max_token_value))
        ->when(isset($request->user_recive_per_hour),fn($query) =>  $query->where("user_recive_per_hour", $request->user_recive_per_hour))
        ->with(["farm:id,name"])
        ->get();

        return $this->api(ProductResource::collection($product),__METHOD__);
    }

    /**
     * product/store
     * @param \App\Http\Requests\V1\Admin\Products\StoreProductRequest $request
     * @param \App\Models\Products $products
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request,Products $product)
    {
        $product = $product->addNewProduct($request);
        $product->load("farm:id,name");
        return $this->api(new ProductResource($product->toArray()),__METHOD__);
    }

    /**
     * product/show
     * @param \App\Models\Products $products
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Products $product)
    {
        $product->load("farm:id,name");
        return $this->api(new ProductResource($product->toArray()),__METHOD__);
    }

    /**
     * prodcut/update
     * @param \App\Http\Requests\V1\Admin\Products\UpdateProductRequest $request
     * @param \App\Models\Products $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Products $product)
    {
        $product->updateProduct($request);
        $product->load("farm:id,name");
        return $this->api(new ProductResource($product->toArray()),__METHOD__);
    }

    /**
     * product/destroy
     * @param \App\Models\Products $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Products $product)
    {
        $product->deleteProducts();
        $product->load("farm:id,name");
        return $this->api(new ProductResource($product->toArray()),__METHOD__);
    }
}
