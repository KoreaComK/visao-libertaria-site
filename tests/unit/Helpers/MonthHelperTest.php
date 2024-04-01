<?php

use CodeIgniter\Test\CIUnitTestCase;

class MonthHelperTest extends CIUnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        helper('month_helper');
    }

    public function testMonthTranslation(): void
    {
        $this->assertEquals('Janeiro', month_helper('january'));
        $this->assertEquals('Fevereiro', month_helper('february'));
        $this->assertEquals('Março', month_helper('march'));
        $this->assertEquals('Abril', month_helper('april'));
        $this->assertEquals('Maio', month_helper('may'));
        $this->assertEquals('Junho', month_helper('june'));
        $this->assertEquals('Julho', month_helper('july'));
        $this->assertEquals('Agosto', month_helper('august'));
        $this->assertEquals('Setembro', month_helper('september'));
        $this->assertEquals('Outubro', month_helper('october'));
        $this->assertEquals('Novembro', month_helper('november'));
        $this->assertEquals('Dezembro', month_helper('december'));
    }

    public function testMonthTranslationWithSize(): void
    {
        $this->assertEquals('Jan', month_helper('january', 3));
        $this->assertEquals('Fev', month_helper('february', 3));
        $this->assertEquals('Mar', month_helper('march', 3));
        $this->assertEquals('Abr', month_helper('april', 3));
        $this->assertEquals('Mai', month_helper('may', 3));
        $this->assertEquals('Jun', month_helper('june', 3));
        $this->assertEquals('Jul', month_helper('july', 3));
        $this->assertEquals('Ago', month_helper('august', 3));
        $this->assertEquals('Set', month_helper('september', 3));
        $this->assertEquals('Out', month_helper('october', 3));
        $this->assertEquals('Nov', month_helper('november', 3));
        $this->assertEquals('Dez', month_helper('december', 3));
    }

    public function testMonthTranslationWithUcfirst()
    {
        $this->assertEquals('janeiro', month_helper(mes: 'january', ucfirst: false));
        $this->assertEquals('fevereiro', month_helper(mes: 'february', ucfirst: false));
        $this->assertEquals('março', month_helper(mes: 'march', ucfirst: false));
        $this->assertEquals('abril', month_helper(mes: 'april', ucfirst: false));
        $this->assertEquals('maio', month_helper(mes: 'may', ucfirst: false));
        $this->assertEquals('junho', month_helper(mes: 'june', ucfirst: false));
        $this->assertEquals('julho', month_helper(mes: 'july', ucfirst: false));
        $this->assertEquals('agosto', month_helper(mes: 'august', ucfirst: false));
        $this->assertEquals('setembro', month_helper(mes: 'september', ucfirst: false));
        $this->assertEquals('outubro', month_helper(mes: 'october', ucfirst: false));
        $this->assertEquals('novembro', month_helper(mes: 'november', ucfirst: false));
        $this->assertEquals('dezembro', month_helper(mes: 'december', ucfirst: false));
    }
}