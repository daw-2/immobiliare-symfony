<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Property;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function testBookingCanHaveDaysList(): void
    {
        $booking = new Booking();
        $booking->setStartDate(new \DateTime('2021-06-03'));
        $booking->setEndDate(new \DateTime('2021-06-06'));

        $this->assertCount(3, $booking->getDays());
    }

    public function testBookingCanBeValidatedOrNot()
    {
        $booking1 = new Booking();
        $booking1->setStartDate(new \DateTime('2021-06-03'));
        $booking1->setEndDate(new \DateTime('2021-06-06'));

        $booking2 = new Booking();
        $booking2->setStartDate(new \DateTime('2021-06-10'));
        $booking2->setEndDate(new \DateTime('2021-06-20'));

        $property = new Property();
        $property->addBooking($booking1)->addBooking($booking2);

        $booking = new Booking();
        $booking->setStartDate(new \DateTime('2021-06-08'));
        $booking->setEndDate(new \DateTime('2021-06-10'));
        $booking->setProperty($property);

        $this->assertTrue($booking->isValid());

        $booking->setEndDate(new \DateTime('2021-06-14'));

        $this->assertFalse($booking->isValid());
    }

    public function testDayPriceCanBeCalculated()
    {
        $property = new Property();
        $property->setPrice(500000 * 100);

        $this->assertEquals(139, $property->getDayPrice());
    }
}
