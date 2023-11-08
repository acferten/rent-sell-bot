<?php

namespace Domain\Estate\Builders;


use Illuminate\Database\Eloquent\Builder;

class EstateBuilder extends Builder
{
    public function whereCompany(int $id): self
    {
        return $this->where('company_id', $id);
    }

}
