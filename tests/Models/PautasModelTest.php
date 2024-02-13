<?php

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Config\Services;
use Tests\Support\Libraries\ConfigReader;

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

    public function testContagemReiniciadaNoNovoDia()
    {
        $session = ['ultimo_acesso' => '2024-02-12'];
        $limiteDiario = 5;

        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => 3]);

        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario);
    }

    public function testContagemNaoReiniciadaNoMesmoDia()
    {
        $session = ['ultimo_acesso' => date('Y-m-d')];
        $limiteDiario = 5;

        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => 3]);

        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario);
    }

    private function verificarLimiteDiario($pautasModel, $session, $limiteDiario)
    {
        $currentDate = date('Y-m-d');

        if ($session['ultimo_acesso'] !== $currentDate) {
            $quantidadePautasUltimoDia = 0;
        } else {
            $quantidadePautasUltimoDia = $pautasModel->getPautasPorUsuario($session['id'])[0]['contador'];
        }

        if ($quantidadePautasUltimoDia >= $limiteDiario) {
            $this->fail('O limite diário de pautas foi atingido.');
        }
    }
}
