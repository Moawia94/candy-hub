<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\Associations\CreateRequest;
use GetCandy\Http\Requests\Api\Products\Associations\DeleteRequest;
use GetCandy\Http\Transformers\Fractal\Products\ProductAssociationTransformer;

class ProductAssociationController extends BaseController
{
    /**
     * Handles the request to update a products attributes
     * @param  String        $product
     * @param  UpdateAttributesRequest $request
     * @return Mixed
     */
    public function store($product, CreateRequest $request)
    {
        $result = app('api')->productAssociations()->store($product, $request->all());
        return $this->respondWithCollection($result, new ProductAssociationTransformer);
    }

    /**
     * Handles the request to remove a product association
     * @param  string        $product
     * @param  DeleteRequest $request
     * @return Mixed
     */
    public function destroy($product, DeleteRequest $request)
    {
        $result = app('api')->productAssociations()->destroy($product, $request->associations);
    }
}