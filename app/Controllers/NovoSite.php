<?php
namespace App\Controllers;

use App\Models\ProjetosModel;
use App\Models\ProjetosVideosModel;
use App\Models\ArtigosModel;

class NovoSite extends BaseController
{
    protected $projetosModel;
    protected $projetosVideosModel;
    protected $artigosModel;

    public function __construct()
    {
        helper('_formata_video');
        
        $this->projetosModel = new ProjetosModel();
        $this->projetosVideosModel = new ProjetosVideosModel();
        $this->artigosModel = new ArtigosModel();
    }

    public function index()
    {
        // Buscar todos os projetos
        $projetos = $this->projetosModel->findAll();
        
        // Array para armazenar os vídeos por projeto
        $data['videos_por_projeto'] = [];
        
        // Para cada projeto, buscar os últimos 10 vídeos
        foreach ($projetos as $projeto) {
            if($projeto['listar'] == 'S') {
                $videos = $this->projetosVideosModel
                ->where('projetos_id', $projeto['id'])
                ->orderBy('publicado', 'DESC')
                ->limit(10)->get()->getResultArray();
            
                // Adicionar os vídeos ao array com o nome do projeto como chave
                $data['videos_por_projeto'][$projeto['nome']]['videos'] = $videos;
            }
        }

        // Buscar os últimos 10 artigos
        $data['ultimos_artigos'] = $this->artigosModel
            ->whereIn('fase_producao_id', array(6,7)) // Apenas artigos ativos
            ->where('descartado',null)
            ->orderBy('publicado', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Query para vídeos em destaque (mantém a original)
        $subquery = $this->projetosModel->db->table('projetos')
            ->select('projetos.*, projetos_videos.*, ROW_NUMBER() OVER(PARTITION BY projetos_videos.projetos_id ORDER BY projetos_videos.publicado DESC) as rn')
            ->join('projetos_videos', 'projetos_videos.projetos_id = projetos.id')
            ->getCompiledSelect();

        $data['videos_destaque'] = $this->projetosModel->db->table("({$subquery}) as ranked_videos")
            ->whereIn('rn', array(1,2))
            ->get()
            ->getResultArray();
        
        $data['active_menu'] = 'home';
        
        return view('_home', $data);
    }

    public function videos($projeto = null) 
    {
        // Verificar se é uma requisição AJAX para infinite scroll
        if ($this->request->isAJAX()) {
            return $this->videosAjax($projeto);
        }

        // Buscar todos os projetos
        $data['projetos'] = $this->projetosModel->findAll();
        
        // Configurar a query base
        $this->projetosVideosModel->select('projetos_videos.*, projetos.nome as projeto_nome');
        $this->projetosVideosModel->join('projetos', 'projetos.id = projetos_videos.projetos_id');
        $this->projetosVideosModel->orderBy('projetos_videos.publicado', 'DESC');
        
        // Se um projeto específico foi solicitado, filtrar
        if ($projeto !== null) {
            $projeto_decoded = urldecode($projeto);
            $this->projetosVideosModel->where('projetos.nome', $projeto_decoded);
            $data['projeto_atual'] = $projeto_decoded;
        }
        
        $data['videosList'] = [
            'videos' => $this->projetosVideosModel->paginate(10),
            'pager' => $this->projetosVideosModel->pager
        ];

        $data['colaboradores'] = $this->session->get('colaboradores');

        $data['active_menu'] = 'videos';
        
        return view('_videos', $data);
    }

    private function videosAjax($projeto = null)
    {
        // Configurar a query base
        $this->projetosVideosModel->select('projetos_videos.*, projetos.nome as projeto_nome');
        $this->projetosVideosModel->join('projetos', 'projetos.id = projetos_videos.projetos_id');
        $this->projetosVideosModel->orderBy('projetos_videos.publicado', 'DESC');
        
        // Se um projeto específico foi solicitado, filtrar
        if ($projeto !== null) {
            $projeto_decoded = urldecode($projeto);
            $this->projetosVideosModel->where('projetos.nome', $projeto_decoded);
        }
        
        $videos = $this->projetosVideosModel->paginate(10);
        $pager = $this->projetosVideosModel->pager;
        
        // Retornar apenas os vídeos em HTML para o infinite scroll
        $html = '';
        foreach ($videos as $video) {
            $titulo = htmlspecialchars($video['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
            $projeto_nome = htmlspecialchars($video['projeto_nome'] ?? 'Projeto', ENT_QUOTES, 'UTF-8');
            $video_id = $video['video_id'] ?? '';
            $publicado = $video['publicado'] ?? '';
            
            $html .= '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
            $html .= '<div class="card video-card h-100">';
            $html .= '<div class="video-thumbnail">';
            $html .= '<img src="' . cria_url_thumb($video_id) . '" alt="' . $titulo . '" class="card-img-top">';
            $html .= '<div class="play-overlay">';
            $html .= '<i class="bi bi-play-circle-fill play-icon"></i>';
            $html .= '<a href="' . cria_link_watch($video_id) . '" class="gen-video-popup" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>';
            $html .= '</div>';
            $html .= '<div class="project-badge">' . $projeto_nome . '</div>';
            $html .= '</div>';
            $html .= '<div class="card-body d-flex flex-column">';
            $html .= '<h6 class="card-title">' . $titulo . '</h6>';
            $html .= '<p class="card-text text-muted small">' . date('d/m/Y', strtotime($publicado)) . '</p>';
            $html .= '<div class="mt-auto">';
            $html .= '<a href="' . cria_link_watch($video_id) . '" class="gen-button gen-video-popup">';
            $html .= '<div class="gen-button-block">';
            $html .= '<span class="gen-button-line-left"></span>';
            $html .= '<span class="gen-button-text">Assistir</span>';
            $html .= '</div>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        return $this->response->setJSON([
            'html' => $html,
            'hasMore' => $pager->hasMore(),
            'currentPage' => $pager->getCurrentPage(),
            'totalPages' => $pager->getPageCount()
        ]);
    }

} 