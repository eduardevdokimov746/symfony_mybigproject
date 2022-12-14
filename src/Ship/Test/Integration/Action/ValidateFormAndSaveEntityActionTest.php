<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Action;

use App\Ship\Action\ValidateFormAndSaveEntityAction;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Stub;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ValidateFormAndSaveEntityActionTest extends KernelTestCase
{
    private ValidateFormAndSaveEntityAction $validateFormAndSaveEntityAction;
    private FormInterface&Stub $form;

    protected function setUp(): void
    {
        $this->form = self::createStub(FormInterface::class);

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $eventDispatcher = self::getContainer()->get(EventDispatcherInterface::class);
        $logger = self::getContainer()->get(LoggerInterface::class);

        $this->validateFormAndSaveEntityAction = new ValidateFormAndSaveEntityAction($eventDispatcher, $logger);
        $this->validateFormAndSaveEntityAction->setEntityManager($entityManager);
    }

    public function testRunFormWithNotEntity(): void
    {
        $this->form->method('getData')->willReturnOnConsecutiveCalls($this, ['name' => 'user']);

        self::assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
        self::assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
        self::assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
    }

    public function testRunFormWithEntity(): void
    {
        $this->form->method('getData')->willReturn(self::createUser());

        self::assertTrue($this->validateFormAndSaveEntityAction->run($this->form));
    }
}
