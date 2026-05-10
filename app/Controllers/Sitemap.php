<?php

namespace App\Controllers;

use App\Models\ArtigosModel;
use App\Models\ProjetosModel;
use App\Models\ProjetosVideosModel;
use App\Models\PaginasEstaticasModel;
use App\Models\ColaboradoresModel;
use RumenX\Sitemap\Sitemap;
use RumenX\Sitemap\Url;

class Sitemap extends BaseController
{
    protected $artigosModel;
    protected $projetosModel;
    protected $projetosVideosModel;
    protected $paginasEstaticasModel;
    protected $colaboradoresModel;

    public function __construct()
    {
        $this->artigosModel = new ArtigosModel();
        $this->projetosModel = new ProjetosModel();
        $this->projetosVideosModel = new ProjetosVideosModel();
        $this->paginasEstaticasModel = new PaginasEstaticasModel();
        $this->colaboradoresModel = new ColaboradoresModel();
    }

    public function index()
    {
        // Create a new sitemap instance
        $sitemap = new Sitemap();

        // Add static pages
        $sitemap->addUrl(new Url([
            'loc' => base_url(),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ]));
        
        $sitemap->addUrl(new Url([
            'loc' => base_url('site/artigos'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '0.9'
        ]));
        
        $sitemap->addUrl(new Url([
            'loc' => base_url('site/videos'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '0.9'
        ]));
        
        $sitemap->addUrl(new Url([
            'loc' => base_url('site/links'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.7'
        ]));
        
        $sitemap->addUrl(new Url([
            'loc' => base_url('site/contato'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.6'
        ]));

        // Add published articles
        $artigos = $this->artigosModel
            ->select('artigos.url_friendly, artigos.publicado')
            ->whereIn('artigos.fase_producao_id', [6, 7])
            ->where('artigos.descartado IS NULL')
            ->orderBy('artigos.publicado', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($artigos as $artigo) {
            $sitemap->addUrl(new Url([
                'loc' => base_url('site/artigo/' . $artigo['url_friendly']),
                'lastmod' => date('Y-m-d', strtotime($artigo['publicado'])),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ]));
        }

        // Add projects and their videos (only if project has 'listar' = 'S')
        $projetos = $this->projetosModel->where('listar', 'S')->findAll();

        foreach ($projetos as $projeto) {
            // Add project page
            $sitemap->addUrl(new Url([
                'loc' => base_url('site/videos/' . urlencode($projeto['nome'])),
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ]));

            // Add videos from this project
            $videos = $this->projetosVideosModel
                ->where('projetos_id', $projeto['id'])
                ->orderBy('publicado', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($videos as $video) {
                $sitemap->addUrl(new Url([
                    'loc' => cria_link_watch($video['video_id']),
                    'lastmod' => date('Y-m-d', strtotime($video['publicado'])),
                    'changefreq' => 'monthly',
                    'priority' => '0.6'
                ]));
            }
        }

        // Add static pages
        $paginasEstaticas = $this->paginasEstaticasModel
            ->where('publico', 1) // Assuming there's a public field to determine if page should be indexed
            ->orWhere('publico IS NULL') // Or if not set, assume public
            ->findAll();

        foreach ($paginasEstaticas as $pagina) {
            $sitemap->addUrl(new Url([
                'loc' => base_url('site/pagina/' . $pagina['url_friendly']),
                'lastmod' => date('Y-m-d', strtotime($pagina['atualizado'])),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ]));
        }

        // Add colaborador profiles (assuming public profiles)
        $colaboradores = $this->colaboradoresModel
            ->where('confirmado_data IS NOT NULL') // Only confirmed users
            ->where('excluido IS NULL') // Not deleted
            ->findAll();

        foreach ($colaboradores as $colaborador) {
            $sitemap->addUrl(new Url([
                'loc' => base_url('site/escritor/' . urlencode($colaborador['apelido'])),
                'lastmod' => date('Y-m-d', strtotime($colaborador['atualizado'])),
                'changefreq' => 'weekly',
                'priority' => '0.5'
            ]));
        }

        // Set content type as XML
        $this->response->setContentType('application/xml');

        // Generate the sitemap XML
        return $this->response->setBody($sitemap->generate());
    }
}