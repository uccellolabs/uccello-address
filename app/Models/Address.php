<?php

namespace Uccello\Address\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return trim($this->address_1 . ' ' . $this->address_2 . ' - ' . $this->postal_code . ' ' . $this->city);
    }
}
