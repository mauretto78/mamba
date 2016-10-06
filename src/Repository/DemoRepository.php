<?php

/*
 * This is an sample Repository only for demo purposes.
 *
 * Remove this before flight.
 */

namespace Mamba\Repository;

use Doctrine\ORM\EntityRepository;

class DemoRepository extends EntityRepository
{
    public function yourCustomMethod()
    {
        return 'silence is golden.';
    }
}

