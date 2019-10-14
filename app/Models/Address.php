<?php

namespace Uccello\Address\Models;

use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\UccelloModule;

class Address extends Model
{
    use UccelloModule;

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
        // return trim($this->address_1 . ' ' . $this->address_2 . ' - ' . $this->postal_code . ' ' . $this->city);
        return trim($this->address_1 . ' ' . $this->city);
    }
}
