<?php

namespace App\Entity\Traits\Timestamping;

/**
 * ThisTrait adds Timestampable fields to entity.
 */
trait TimestampableTrait
{
    use AutoCreatedAtTrait, AutoUpdatedAtTrait;
}