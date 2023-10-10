<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine;

use App\Entity\User;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{
    final public const SUPER_ADMIN_USER = 'user.super_admin';

    private const DEFAULT_DATA = [
        'password' => '$2y$04$l47iOLckZ677ReRY0mNSJemCFZEf7TLA6w0HrDFbl0vhS42VexVU6',
    ];

    private const ROWS = [
        self::SUPER_ADMIN_USER => [
            'firstName' => 'Super',
            'lastName' => 'Admin',
            'email' => 'super-admin@hm.mail',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $row = $this->merge(self::DEFAULT_DATA, $row);

            $user = new User();
            $this->merge($row, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
