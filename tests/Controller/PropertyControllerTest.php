<?php

namespace App\Tests\Controller;

use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PropertyControllerTest extends WebTestCase
{
    public function testBookingCanBeCreated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/annonce/aut-harum-et-assumenda-dicta-sit-amet_914');
        $client->loginUser(static::$container->get(UserRepository::class)->findOneByEmail('matthieu@boxydev.com'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Reprehenderit sed perferendis autem repellat omnis consequatur illum quia.');

        $crawler = $client->submitForm('Réserver', [
            'booking[startDate]' => '2021-06-03',
            'booking[endDate]' => '2021-06-05',
        ]);

        $booking = static::$container->get(BookingRepository::class)->findOneByStartDate(new \DateTime('2021-06-03'));
        $this->assertEquals(228, $booking->getPrice());

        static::$container->get(EntityManagerInterface::class)->remove($booking);
        static::$container->get(EntityManagerInterface::class)->flush();
    }

    public function testBookingCannotBeCreatedIfEndDateIsInvalid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/annonce/aut-harum-et-assumenda-dicta-sit-amet_914');
        $client->loginUser(static::$container->get(UserRepository::class)->findOneByEmail('matthieu@boxydev.com'));

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Reprehenderit sed perferendis autem repellat omnis consequatur illum quia.');

        $crawler = $client->submitForm('Réserver', [
            'booking[startDate]' => '2021-06-05',
            'booking[endDate]' => '2021-06-03',
        ]);

        $this->assertSelectorTextContains('.form-error-message', 'Cette valeur doit être supérieure ou égale à 5 juin 2021, 00:00.');
    }
}
