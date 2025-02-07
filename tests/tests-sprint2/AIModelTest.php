<?php

use PHPUnit\Framework\TestCase;
use App\Models\AIModel;

class AIModelTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        $this->model = new AIModel();
    }

    public function testInputValidation()
    {
        $this->assertTrue(
            $this->model->validateInput(50, 0, 100),
            'Debería aceptar valores dentro del rango'
        );
        
        $this->assertFalse(
            $this->model->validateInput(-1, 0, 100),
            'Debería rechazar valores fuera del rango'
        );
    }

    public function testDataNormalization()
    {
        $this->assertEquals(
            0.5,
            $this->model->normalize(50, 0, 100),
            'Debería normalizar correctamente los valores'
        );
    }
}