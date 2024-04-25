<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;


class UserCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    private $status;
    private $statusCode;
    public function __construct($resource, $statusCode = 200, $status = 'success')
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->statusCode = $statusCode;
    }
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'data' => $this->collection,
            'status' =>  $this->statusCode,
            'title' => $this->status,
            'count' => $this->collection->count(),
        ];
    }
}