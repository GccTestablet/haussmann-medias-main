<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine;

use App\Entity\User;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const SUPER_ADMIN_USER = 'user.super_admin';
    final public const SIMPLE_USER = 'user.simple_user';

    private const DEFAULT_DATA = [
        'password' => '$2y$13$vmqxI57bdK3Blb18X9kjmOBFHL0B6bnokvBG9rNHY.GiaiiI47gBy', // Qwerty123
    ];

    private const ROWS = [
        self::SUPER_ADMIN_USER => [
            'firstName' => 'SUPER',
            'lastName' => 'Admin',
            'email' => 'super-admin@hm.mail',
            'roles' => ['ROLE_SUPER_ADMIN'],
            'connectedOn' => CompanyFixture::HAUSSMANN_MEDIAS,
        ],
        self::SIMPLE_USER => [
            'firstName' => 'SIMPLE',
            'lastName' => 'User',
            'email' => 'simple-user@hm.mail',
            'passwordResetToken' => 'token',
            'connectedOn' => CompanyFixture::HAUSSMANN_MEDIAS,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $row = $this->merge(self::DEFAULT_DATA, $row);
            $this->denormalizeReferenceFields($row, [
                'connectedOn',
            ]);

            $user = new User();
            $this->merge($row, $user);

            $manager->persist($user);
            $this->setReference($reference, $user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixture::class,
        ];
    }
}
