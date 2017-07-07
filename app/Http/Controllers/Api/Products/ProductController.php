<?php

namespace GetCandy\Http\Controllers\Api\Products;

use GetCandy\Exceptions\InvalidLanguageException;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Products\CreateRequest;
use GetCandy\Http\Requests\Api\Products\DeleteRequest;
use GetCandy\Http\Requests\Api\Products\UpdateAttributesRequest;
use GetCandy\Http\Requests\Api\Products\UpdateCollectionsRequest;
use GetCandy\Http\Requests\Api\Products\UpdateRequest;
use GetCandy\Http\Transformers\Fractal\Products\ProductTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends BaseController
{
    /**
     * Handles the request to show all products
     * @param  Request $request
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->products()->getPaginatedData($request->keywords, $request->per_page);
        // event(new ViewProductEvent(['hello' => 'there']));
        return $this->respondWithCollection($paginator, new ProductTransformer);
    }

    /**
     * Handles the request to show a product based on hashed ID
     * @param  String $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $product = app('api')->products()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($product, new ProductTransformer);
    }

    /**
     * Handles the request to create a new product
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        try {
            $result = app('api')->products()->create($request->all());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to update a product
     * @param  String        $id
     * @param  UpdateRequest $request
     * @return Json
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->products()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (InvalidLanguageException $e) {
            return $this->errorUnprocessable($e->getMessage());
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to update a products attributes
     * @param  String        $id
     * @param  UpdateAttributesRequest $request
     * @return Json
     */
    public function updateAttributes($product, UpdateAttributesRequest $request)
    {
        try {
            $result = app('api')->products()->updateAttributes($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to update a products collections
     * @param  String        $id
     * @param  UpdateCollectionsRequest $request
     * @return Json
     */
    public function updateCollections($product, UpdateCollectionsRequest $request)
    {
        try {
            $result = app('api')->products()->updateCollections($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithItem($result, new ProductTransformer);
    }

    /**
     * Handles the request to delete a product
     * @param  String        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, DeleteRequest $request)
    {
        try {
            $result = app('api')->products()->delete($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return $this->respondWithNoContent();
    }
}