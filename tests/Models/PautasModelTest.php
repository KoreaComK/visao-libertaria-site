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
        // Configuração do cenário
        $session = ['id' => 1];
        $limiteDiario = 5;

        // Mock do método getPautasPorUsuario
        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => 3, 'ultima_pauta_data' => '2024-02-12']);

        // Execução da lógica
        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario);
    }

    public function testContagemNaoReiniciadaNoMesmoDia()
    {
        // Configuração do cenário
        $session = ['id' => 1];
        $limiteDiario = 5;

        // Mock do método getPautasPorUsuario
        $this->pautasModelMock->expects($this->atLeastOnce())
            ->method('getPautasPorUsuario')
            ->willReturn(['contador' => 3, 'ultima_pauta_data' => date('Y-m-d')]);

        // Execução da lógica
        $this->verificarLimiteDiario($this->pautasModelMock, $session, $limiteDiario);
    }

    private function verificarLimiteDiario($pautasModel, $session, $limiteDiario)
    {
        $currentDate = date('Y-m-d');

        // Suponhamos que a data da última pauta seja obtida da função getPautasPorUsuario
        $ultimaPautaData = $pautasModel->getPautasPorUsuario($session['id'])[0]['ultima_pauta_data'];

        if ($ultimaPautaData !== $currentDate) {
            // Se a data for diferente, zera a contagem de pautas
            $quantidadePautasUltimoDia = 0;
        } else {
            $quantidadePautasUltimoDia = $pautasModel->getPautasPorUsuario($session['id'])[0]['contador'];
        }

        if ($quantidadePautasUltimoDia >= $limiteDiario) {
            $this->fail('O limite diário de pautas foi atingido.');
        }
    }
}