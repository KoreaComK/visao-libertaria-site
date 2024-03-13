<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Config\Database;
use App\Models\ConfiguracaoModel;
use CodeIgniter\Database\Migration;

final class CadastraConfiguracoes extends Migration
{
    public function up(): void
    {
        $configuracaoModel = new ConfiguracaoModel();

        // Não duplica as configurações iniciais no banco de dados de produção
        if ($configuracaoModel->countAllResults() > 0) {
            return;
        }

        $configuracoes = [
            [
                'config' => 'artigo_regras_escrever',
                'config_valor' => '<h3>Atenção ao escrever artigos</h3>\r\n <p>O estilo geral de artigos no Visão Libertária mudou. Note que artigos precisam seguir o tipo de artigo e\r\n estilo do canal. Precisam ter uma visão libertária em algum ponto. Apenas notícia, não serve.</p>\r\n <ul>\r\n <li>Buscar escrever artigos lógicos, com contribuição, citando notícia ou fato, desenvolvimento, com visão\r\n libertária, e conclusão.</li>\r\n <li>Use corretores ortográficos para evitar erros de grafia e concordância.</li>\r\n <li>Evite frases longas. Elas podem funcionar em livros, mas, para ser narrada, precisa ter, no máximo 30\r\n palavras.</li>\r\n </ul>\r\n <p>Além disso, apesar de ter sido feito no passado, nosso estilo atual modou:</p>\r\n <ul>\r\n <li>Não incluir mugidos e imitações de caracteres cômicos.</li>\r\n <li>Não usar apelidos de países. A primeira vez que citar um país no texto usar expressões como \"aquele país\r\n que estatistas chamam de xxxx\" ou frases similares.</li>\r\n <li>Evitar termos que possam ter interpretações erradas, como rato, macaco, porco, etc.</li>\r\n <li>Termos como bovino gadoso, rei do gado, parasita público, mafioso, fraudemia, etc, bem como palavrões\r\n leves ou pesados, devem ser usados com moderação. Nunca usados nos primeiros 30 segundos do texto, ou\r\n seja, título, gancho, e nas primeiras 200 palavras.</li>\r\n <li>Não usar nomes satíricos para políticos ou pessoas do público. Evitar usar nome de pessoas que não são\r\n políticos ou pessoas muito conhecidas.</li>\r\n </ul>',
            ],
            [
                'config' => 'artigo_regras_revisar',
                'config_valor' => '<h3>Informações importantes sobre a revisão de artigos.</h3>\r\n <p>O texto precisa ser revisado, relendo-se todo o texto e corrigindo pontos necessários para</p>\r\n <ul>\r\n <li>Ortografia deve estar perfeita, pode-se revisar usando o MS Word.</li>\r\n <li>Concordância verbal e outras regras de gramática estão boas.</li>\r\n <li>Narração sempre usa ou todos os verbos em terceira pessoa ou todos em primeira pessoa, jamais\r\n misturando.</li>\r\n <li>Mugidos e vozes de personagens são terminantemente proibidos.</li>\r\n </ul>\r\n <p>Lembre-se: você não pode revisar seu próprio texto. É notório que quando a pessoa revisa seu texto, por mais\r\n que se esforce, não gera uma revisão tão eficiente.</p>\r\n <p>Qualquer dúvida converse com o pessoal no Discord no fórum #duvidas-revisao ou tire suas dúvidas no e-mail:\r\n anpsu@gmail.com</p>\r\n <p>Além disso, apesar de ter sido feito no passado, nosso estilo atual mudou.</p>\r\n <ul>\r\n <li>Não incluir mugidos e imitações de caracteres cômicos.</li>\r\n <li>Não usar apelidos de países. A primeira vez que citar um país no texto usar expressões como \"aquele país\r\n que estatistas chamam de xxxx\" ou frases similares.</li>\r\n <li>Evitar termos que possam ter interpretações erradas, como rato, macaco, porco, etc.</li>\r\n <li>Termos como bovino gadoso, rei do gado, parasita público, mafioso, fraudemia, etc, bem como palavrões\r\n leves ou pesados, devem ser usados com moderação. Nunca usados nos primeiros 30 segundos do texto, ou\r\n seja, título, gancho, e nas primeiras 200 palavras.</li>\r\n <li>Não usar nomes satíricos para políticos ou pessoas do público. Evitar usar nome de pessoas que não são\r\n políticos ou pessoas muito conhecidas.</li>\r\n </ul>',
            ],
            [
                'config' => 'artigo_regras_narrar',
                'config_valor' => '',
            ],
            [
                'config' => 'artigo_regras_produzir',
                'config_valor' => '',
            ],
            [
                'config' => 'artigo_tamanho_maximo',
                'config_valor' => '2500',
            ],
            [
                'config' => 'artigo_tamanho_minimo',
                'config_valor' => '1000',
            ],
            [
                'config' => 'artigo_visualizacao_narracao',
                'config_valor' => '{gancho}\r\nEste é o Visão Libertária, sua fonte de informações descentralizadas e distribuídas.\r\n{texto}\r\nObrigado por sua audiência.\r\nEste artigo foi {colaboradores}\r\nSe você gostou do vídeo, compartilhe em suas redes sociais. Caso deseje ser avisado de outros vídeos, inscreva-se no canal, e depois clique no botão da campainha. Escreva artigos você também, acesse visão libertária ponto com. Até a próxima.',
            ],
            [
                'config' => 'contato_email',
                'config_valor' => 'email@email.com',
            ],
            [
                'config' => 'contato_email_copia',
                'config_valor' => 'email2@email2.com',
            ],
            [
                'config' => 'cron_hash',
                'config_valor' => 'ADICIONE A SUA HASH AQUI',
            ],
            [
                'config' => 'cron_pautas_data_delete',
                'config_valor' => '7 days',
            ],
            [
                'config' => 'cron_pautas_status_delete',
                'config_valor' => '1',
            ],
            [
                'config' => 'descricao_padrao_youtube',
                'config_valor' => 'Referências:\r\n{referencias}\r\n\r\nVeja nosso site: http://visaolibertaria.com\r\n\r\nCamisas e merchandising: http://pimentanocafe.com.br/visaolibe...\r\n\r\n{tags}\r\n\r\nAjude o canal:\r\n\r\nhttps://picpay.me/ancapsu\r\nhttps://padrim.com.br/ancapsu\r\n16vmNcrA4Mvf7CaRLirAmpnjz1ZH3bWNkQ (Bitcoin)\r\nLSCnrubCVcpuLrGTTLMqpwRTXvYz7vMbbA (Litecoin)\r\n0x28aec946919c70e5e25d7c6785ede7622278b463 (Ethereum)\r\nnano_1s6i6xwujzqnmie8nc3x8rfumdnebpsd6h4cp9wgkcpk4eb1xn5u7n48ok5b (Nano)\r\n0x1302536c28695e47aaedd020aaf39f500cf6c22f (BNB)',
            ],
            [
                'config' => 'home_banner',
                'config_valor' => '5',
            ],
            [
                'config' => 'home_banner_mostrar',
                'config_valor' => '1',
            ],
            [
                'config' => 'home_newsletter_mostrar',
                'config_valor' => '0',
            ],
            [
                'config' => 'home_talvez_goste',
                'config_valor' => '3',
            ],
            [
                'config' => 'home_talvez_goste_mostrar',
                'config_valor' => '1',
            ],
            [
                'config' => 'home_ultimos_videos',
                'config_valor' => '10',
            ],
            [
                'config' => 'home_ultimos_videos_mostrar',
                'config_valor' => '1',
            ],
            [
                'config' => 'limite_pautas_diario',
                'config_valor' => '5',
            ],
            [
                'config' => 'limite_pautas_semanal',
                'config_valor' => '20',
            ],
            [
                'config' => 'link_instagram',
                'config_valor' => 'https://www.instagram.com',
            ],
            [
                'config' => 'link_twitter',
                'config_valor' => 'https://twitter.com',
            ],
            [
                'config' => 'link_youtube',
                'config_valor' => 'https://www.youtube.com',
            ],
            [
                'config' => 'pauta_dias_antigo',
                'config_valor' => '5',
            ],
            [
                'config' => 'pauta_tamanho_maximo',
                'config_valor' => '100',
            ],
            [
                'config' => 'pauta_tamanho_minimo',
                'config_valor' => '10',
            ],
            [
                'config' => 'site_quantidade_listagem',
                'config_valor' => '12',
            ],
            [
                'config' => 'site_nome',
                'config_valor' => json_encode([
                    'ancap.su' => 'ANCAP.SU',
                    'ancapsu.com' => 'ANCAP.SU',
                    'safesrc.com' => 'Safe Source',
                    'visaolibertaria.com' => 'Visão Libertária',
                    'wrevolving.com' => 'Mundo Em Revolução',
                    'default' => 'Visão Libertária',
                ]),
            ],
            [
                'config' => 'site_descricao',
                'config_valor' => json_encode([
                    'ancap.su' => 'ANCAP.SU',
                    'ancapsu.com' => 'ANCAP.SU',
                    'safesrc.com' => 'Safe Source',
                    'visaolibertaria.com' => 'Visão Libertária',
                    'wrevolving.com' => 'Mundo Em Revolução',
                    'default' => 'Visão Libertária',
                ]),
            ],
            [
                'config' => 'texto_informacao_perfil',
                'config_valor' => 'Leia nossas diretrizes para aceitar artigos no Visão Libertária, clicando aqui.\r\n\r\nVeja as diretrizes e cuidados para ser um revisor, clicando aqui.\r\n\r\nSaiba as configurações e definições para enviar seu arquivo de áudio, clicando aqui.\r\n\r\nEncontre todos os parâmetros e insumos para produzir os vídeos do canal, clicando aqui.',
            ],
            [
                'config' => 'pauta_bot_hash',
                'config_valor' => 'ADICIONE A SUA HASH AQUI',
            ],
            [
                'config' => 'cron_artigos_desmarcar_status',
                'config_valor' => '1',
            ],
            [
                'config' => 'cron_artigos_desmarcar_data_revisao',
                'config_valor' => '24 hours',
            ],
            [
                'config' => 'cron_artigos_desmarcar_data_narracao',
                'config_valor' => '6 hours',
            ],
            [
                'config' => 'cron_artigos_desmarcar_data_producao',
                'config_valor' => '12 hours',
            ],
            [
                'config' => 'cron_email_carteira',
                'config_valor' => '',
            ],
            [
                'config' => 'artigo_tempo_bloqueio',
                'config_valor' => '48 hours',
            ]
        ];

        foreach ($configuracoes as $configuracao) {
            $configuracaoModel->insert($configuracao);
        }
    }

    public function down(): void
    {
        Database::connect()->table('configuracao')->truncate();
    }
}
