<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Action;

use App\Ship\Action\ValidateFormAndSaveEntityAction;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ValidateFormAndSaveEntityActionTest extends KernelTestCase
{
    private ValidateFormAndSaveEntityAction $validateFormAndSaveEntityAction;
    private FormInterface $form;

    protected function setUp(): void
    {
        $this->form = $this->createStub(FormInterface::class);
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->validateFormAndSaveEntityAction = new ValidateFormAndSaveEntityAction($entityManager);
    }

    public function testRunFormWithNotEntity(): void
    {
        $this->form->method('getData')->willReturnOnConsecutiveCalls($this, new class {}, ['name' => 'user']);

        $this->assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
        $this->assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
        $this->assertFalse($this->validateFormAndSaveEntityAction->run($this->form));
    }

    public function testRunFormWithEntity(): void
    {
        $this->form->method('getData')->willReturn($this->createUser());

        $this->assertTrue($this->validateFormAndSaveEntityAction->run($this->form));
    }
}
