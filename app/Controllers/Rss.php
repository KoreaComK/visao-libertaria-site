<?php

namespace App\Controllers;

use App\Models\ArtigosModel;
use Laminas\Feed\Writer\Feed;

class Rss extends BaseController
{
    protected $artigosModel;

    public function __construct()
    {
        $this->artigosModel = new ArtigosModel();
    }

    public function index()
    {
        // Get published articles (fase_producao_id in 6,7 and not discarded)
        $artigos = $this->artigosModel
            ->select('artigos.id, artigos.titulo, artigos.url_friendly, artigos.texto, artigos.imagem, artigos.publicado, colaboradores.apelido as autor')
            ->join('colaboradores', 'colaboradores.id = artigos.escrito_colaboradores_id')
            ->whereIn('artigos.fase_producao_id', [6, 7])
            ->where('artigos.descartado IS NULL')
            ->orderBy('artigos.publicado', 'DESC')
            ->limit(50) // Limit to last 50 articles
            ->get()
            ->getResultArray();

        // Create RSS feed using Laminas Feed
        $feed = new Feed();
        $feed->setTitle('Visão Libertária - RSS Feed');
        $feed->setDescription('Últimos artigos publicados no site Visão Libertária');
        $feed->setLink(base_url());
        $feed->setDateModified(time());
        $feed->setLanguage('pt-BR');
        $feed->setGenerator('Visão Libertária CMS');

        foreach ($artigos as $artigo) {
            $entry = $feed->createEntry();
            $entry->setTitle($artigo['titulo']);
            $entry->setDescription(strip_tags(substr($artigo['texto'], 0, 500)) . '...');
            $entry->setLink(base_url('site/artigo/' . $artigo['url_friendly']));
            $entry->setDateCreated(strtotime($artigo['publicado']));
            $entry->setDateModified(strtotime($artigo['publicado']));
            $entry->setAuthor([
                'name' => $artigo['autor']
            ]);

            // Add enclosure if image exists
            if (!empty($artigo['imagem'])) {
                $entry->addEnclosure([
                    'uri' => $artigo['imagem'],
                    'type' => 'image/jpeg',
                    'length' => 0
                ]);
            }

            $feed->addEntry($entry);
        }

        // Set content type as RSS
        $this->response->setContentType('application/rss+xml');

        return $this->response->setBody($feed->export('rss'));
    }
}