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
            'email' => 'user_one@example.com',
            'customer' => 0,
            'registeredAt' => '2020-12-01',
        ],
        [
            'email' => 'user_two@example.com',
            'customer' => 0,
            'registeredAt' => '2020-12-15',
        ],
        [
            'email' => 'user_three@example.com',
            'customer' => 0,
            'registeredAt' => '2021-01-01',
        ],
        [
            'email' => 'user_four@example.com',
            'customer' => 0,
            'registeredAt' => '2020-09-01',
        ],
        [
            'email' => 'user_five@example.com',
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
            'activateAt' => '2021-01-10',
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
            'appointmentAt' => '2021-01-22',
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
                $sessionData['activateAt'] ? new DateTime($sessionData['activateAt']) : null,
                $sessionData['appointmentAt'] ? new DateTime($sessionData['appointmentAt']) : null
            );
        }

        $manager->flush();
    }
}
