<?php

declare(strict_types=1);

namespace App\Tests\Editorial\Domain\Model;

use App\Editorial\Domain\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testCreate(): void
    {
        $id = 1;
        $firstname = 'Han';
        $lastname = 'Solo';

        $user = User::create($id, $firstname, $lastname);

        $this->assertEquals($id, $user->id());
        $this->assertEquals($firstname, $user->firstname());
        $this->assertEquals($lastname, $user->lastname());
    }
}