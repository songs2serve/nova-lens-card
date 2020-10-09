<?php

namespace Songs2Serve\NovaLensCard;

use Laravel\Nova\Card;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use LogicException;

class LensCard extends Card
{
    public Lens $lens;
    public ?string $modelClass;
    public int $limit = 5;
    public ?string $name = null;

    public function __construct(Lens $lens, ?string $modelClass = null)
    {
        parent::__construct();

        $this->lens = $lens;
        $this->modelClass = $modelClass;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function component()
    {
        return 'nova-lens-card';
    }

    public function jsonSerialize()
    {
        $request = $this->getLensRequest();
        $resource = $this->getResource($request);

        $data = $request
            ->toResources($this->lens->query($request, $resource->newQuery()->limit($this->limit))->get())
            ->map(function ($row) {
                $row['authorizedToDelete'] = false;
                $row['authorizedToUpdate'] = false;

                return $row;
            });

        return array_merge([
            'name' => $this->getName(),
            'lens' => $this->lens->jsonSerialize(),
            'resources' => $data,
            'resourceName' => $resource->uriKey(),
        ], parent::jsonSerialize());
    }

    private function getLensRequest(): LensRequest
    {
        $request = LensRequest::createFrom(request());

        if (! $request->route()->hasParameter('resource') && $this->modelClass === null) {
            throw new LogicException('LensCard requires the model class name, when outside of the resource context.');
        }

        if ($this->modelClass !== null) {
            $request->route()->setParameter('resource', $this->getResource($request)->uriKey());
        }

        $request->route()->setParameter('lens', $this->lens->uriKey());

        return $request;
    }

    private function getName(): string
    {
        return $this->name ?? $this->lens->name();
    }

    private function getResource($request): Resource
    {
        if ($this->modelClass !== null) {
            return Nova::newResourceFromModel(new $this->modelClass);
        }

        return Nova::resourceInstanceForKey($request->route('resource'));
    }
}
