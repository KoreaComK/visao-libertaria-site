<?php

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Config\Services;
use Tests\Support\Libraries\ConfigReader;
use CodeIgniter\I18n\Time;

class PautasModelTest extends CIUnitTestCase
{
    private $pautasModelMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Carrega a configuração do CodeIgniter para usar nos testes
        $config = new App();
        $config->baseURL = 'http://localhost/visao-libertaria-site/';
        Services::injectMock('config', $config);

        // Inicializa o objeto ConfigReader
        $configReader = new ConfigReader();
        $config->config = $configReader->getAppConfig();
        Services::injectMock('config', $config);

        $this->pautasModelMock = $this->getMockBuilder(PautasModel::class)
            ->onlyMethods(['getPautasPorUsuario'])
            ->getMock();
    }

    public function testVerificarLimiteDiarioAtingido()
    {
        // Configuração do cenário
        $session = ['id' => 1];
        $limiteDiario = 5;
        $contadorPautas = 6; // Excede o limite diário

        // Mock do método getPautasPorUsuario
        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => $contadorPautas]);

        // Lógica de verificação do limite diário
        $data = [];
        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario, $data);

        // Verifica se o erro foi configurado corretamente
        $this->assertEquals('O limite diário de pautas foi atingido. Tente novamente amanhã.', $data['erros']);
    }

    public function testVerificarLimiteDiarioNaoAtingido()
    {
        // Configuração do cenário
        $session = ['id' => 1];
        $limiteDiario = 5;
        $contadorPautas = 4; // Não excede o limite diário

        // Mock do método getPautasPorUsuario
        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => $contadorPautas]);

        // Lógica de verificação do limite diário
        $data = [];
        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario, $data);

        // Verifica se não há erro configurado
        $this->assertEmpty($data['erros']);
    }

    private function verificarLimiteDiario($pautasModel, $session, $limiteDiario, &$data)
    {
        // Obtém a quantidade de pautas do usuário para o dia atual
        $time = Time::today();
        $time = $time->toDateTimeString();
        $contadorPautas = $pautasModel->getPautasPorUsuario($time, $session['id'])[0]['contador'];

        // Verifica se a quantidade de pautas do último dia ultrapassou o limite diário
        if ($contadorPautas >= $limiteDiario) {
            $data['erros'] = 'O limite diário de pautas foi atingido. Tente novamente amanhã.';
        }
    }
}
