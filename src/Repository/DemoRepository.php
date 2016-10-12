<?php

namespace Mamba\Repository;

use Doctrine\ORM\EntityRepository;

class DemoRepository extends EntityRepository
{
    public function yourCustomMethod()
    {
        return 'silence is golden.';
    }
}

