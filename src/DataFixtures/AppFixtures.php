<?php

namespace App\DataFixtures;

use App\Factory\CustomerFactory;
use App\Factory\SessionFactory;
use App\Factory\UserFactory;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const CUSTOMERS = [
        [
            'name' => 'Client One'
        ],
        [
            'name' => 'Client Two'
        ],
    ];

    private const USERS = [
        [
            'email' => 'User One',
            'customer' => 0,
            'registeredAt' => '2020-12-01',
        ],
        [
            'email' => 'User Two',
            'customer' => 0,
            'registeredAt' => '2020-12-15',
        ],
        [
            'email' => 'User Three',
            'customer' => 0,
            'registeredAt' => '2021-01-01',
        ],
        [
            'email' => 'User Four',
            'customer' => 0,
            'registeredAt' => '2020-09-01',
        ],
        [
            'email' => 'User Five',
            'customer' => 1,
            'registeredAt' => '2020-12-01',
        ],
    ];

    private const SESSIONS = [
        [
            'user' => 0,
            'activateAt' => '2021-01-15',
            'appointmentAt' => null,
        ],
        [
            'user' => 0,
            'activateAt' => '2021-01-18',
            'appointmentAt' => null,
        ],
        [
            'user' => 1,
            'activateAt' => null,
            'appointmentAt' => '2021-01-15',
        ],
        [
            'user' => 2,
            'activateAt' => '2021-01-01',
            'appointmentAt' => null,
        ],
        [
            'user' => 3,
            'activateAt' => '2020-10-11',
            'appointmentAt' => '2020-12-27',
        ],
        [
            'user' => 3,
            'activateAt' => '2021-01-12',
            'appointmentAt' => '2020-01-22',
        ],
    ];

    public function __construct(
        private CustomerFactory $customerFactory,
        private UserFactory $userFactory,
        private SessionFactory $sessionFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $customers = [];
        foreach (self::CUSTOMERS as $customerData) {
            $customers[] = $this->customerFactory->create($customerData['name']);
        }

        $users = [];
        foreach (self::USERS as $userData) {
            $users[] = $this->userFactory->create(
                $userData['email'],
                $customers[$userData['customer']],
                new DateTime($userData['registeredAt'])
            );
        }

        foreach (self::SESSIONS as $sessionData) {
            $this->sessionFactory->create(
                $users[$sessionData['user']],
                new DateTime($sessionData['activateAt']),
                new DateTime($sessionData['appointmentAt'])
            );
        }

        $manager->flush();
    }
}
