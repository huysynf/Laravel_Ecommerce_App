<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Traits\HandleImageTrait;
use phpDocumentor\Reflection\Types\This;

class ProductService
{
    protected ProductRepository $productRepository;
    use HandleImageTrait;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return mixed
     */
    public function getWithPaginate(): mixed
    {
        return $this->productRepository->getWithPaginate();
    }

    /**
     * @param $request
     * @return mixed
     */

    public function store($request): mixed
    {
        $dataCreate = $request->except('sizes');
        $sizes = $request->sizes ? json_decode($request->sizes) : [];
        $product = $this->productRepository->create($dataCreate);
        $dataCreate['image'] = $this->saveImage($request);
        $this->updateDetail($product, $dataCreate, $sizes);
        return $product;
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request, $id): mixed
    {
        $product = $this->findOrFail($id);
        $dataUpdate = $request->except('sizes');
        $sizes = $request->sizes ? json_decode($request->sizes) : [];
        $dataUpdate['image'] = $this->updateImage($request, $product?->images?->first()?->url);
        $product->update($dataUpdate);
        $this->updateDetail($product, $dataUpdate, $sizes);
        return $product;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        $product = $this->findOrFail($id);
        $product->delete();
        $product->deleteImage();
        $this->deleteImage($product?->images?->first()?->url);

        return $product;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id): mixed
    {
        return $this->productRepository->findOrFail($id);
    }

    /**
     * @param mixed $product
     * @param mixed $dataCreate
     * @param mixed $sizes
     * @return null|Product
     */
    public function updateDetail(Product $product, mixed $dataCreate, mixed $sizes): Product|null
    {
        $product->syncImage($dataCreate['image']);
        $product->assignCategory($dataCreate['category_ids'] ?? []);
        $sizeArray = [];
        foreach ($sizes as $size) {
            $sizeArray[] = ['size' => $size->size, 'quantity' => $size->quantity, 'product_id' => $product->id];
        }

        $product->details()->insert($sizeArray);

        return $product;
    }
}
