<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Artigos extends Seeder
{
	public function run()
	{
		$faker = Factory::create('pt_BR');
		for ($i=0; $i < 1000; $i++) { 
			$data_criado = $faker->dateTimeBetween('-500 days', '-1 days')->format('Y/m/d H:i:s');
			$data_excluido = ($faker->numberBetween(1,100)>95)?($faker->dateTimeBetween('-100 days', '-1 days')->format('Y/m/d H:i:s')):(null);
			
			$data_maior = ($data_excluido==null || $data_criado > $data_excluido)?($data_criado):($data_excluido);

			$titulo = $faker->words(3,true).' '.$faker->name().' '.$faker->words(3,true);
			$url_friendly = preg_replace('/[\@\.\;\" "]+/', '-', $titulo);

			$texto_original = $faker->paragraphs($faker->numberBetween(60,100), true);
			$texto_revisado = $faker->paragraphs($faker->numberBetween(60,100), true);

			$quantidade_palavras_original = count(explode(' ',str_replace('\r\n',' ',$texto_original)));
			$quantidade_palavras_revisado = count(explode(' ',str_replace('\r\n',' ',$texto_revisado)));

			$fase_producao = $faker->numberBetween(1,7);
			$revisado = null;
			$narrado = null;
			$produzido = null;
			$publicado = null;
			$link_produzido = null;
			$arquivo_audio = null;
			$data_publicado = null;
			$link_youtube = null;

			if($fase_producao == 1){
				$texto_revisado = null;
				$quantidade_palavras_revisado = 0;
			}
			if($fase_producao == 2){
				$texto_revisado = null;
				$quantidade_palavras_revisado = 0;
			}
			if($fase_producao == 3){
				$revisado = $faker->numberBetween(1,1000);
			}
			if($fase_producao == 4){
				$revisado = $faker->numberBetween(1,1000);
				$narrado = $faker->numberBetween(1,1000);
				$arquivo_audio = $faker->uuid();
			}
			if($fase_producao == 5){
				$revisado = $faker->numberBetween(1,1000);
				$narrado = $faker->numberBetween(1,1000);
				$produzido = $faker->numberBetween(1,1000);
				$link_produzido = $faker->uuid();
				$arquivo_audio = $faker->uuid();
			}
			if($fase_producao == 6){
				$revisado = $faker->numberBetween(1,1000);
				$narrado = $faker->numberBetween(1,1000);
				$produzido = $faker->numberBetween(1,1000);
				$publicado = $faker->numberBetween(1,1000);
				$link_produzido = $faker->uuid();
				$arquivo_audio = $faker->uuid();
				$data_publicado = $data_maior;
				$link_youtube = $faker->url();
			}
			if($fase_producao == 7){
				$revisado = $faker->numberBetween(1,1000);
				$narrado = $faker->numberBetween(1,1000);
				$produzido = $faker->numberBetween(1,1000);
				$publicado = $faker->numberBetween(1,1000);
				$link_produzido = $faker->uuid();
				$data_publicado = $data_maior;
				$link_youtube = $faker->url();
			}

			$descartado_colaboradores_id = ($data_excluido!==null)?($faker->numberBetween(1,1000)):(null);


			$referencias = null;
			for ($j=0; $j < $faker->numberBetween(1,10); $j++) { 
				$referencias .= $faker->url()."\r\n";
			}


			$data = [
				'id' => $faker->uuid(),
				'url_friendly' => $url_friendly,
				'fase_producao_id' => $fase_producao,
				'link' => $faker->url(),
				'sugerido_colaboradores_id' => $faker->numberBetween(1,1000),
				'titulo' => $titulo,
				'gancho' => $faker->sentence(),
				'texto_original' => $texto_original,
				'referencias' => $referencias,
				'imagem' => $faker->imageUrl(1920,1080, true),
				'escrito_colaboradores_id' => $faker->numberBetween(1,1000),
				'texto_revisado' => $texto_revisado,
				'revisado_colaboradores_id' => $revisado,
				'arquivo_audio' => $arquivo_audio,
				'narrado_colaboradores_id' => $narrado,
				'link_produzido' => $link_produzido,
				'produzido_colaboradores_id' => $produzido,
				'publicado' => $data_publicado,
				'publicado_colaboradores_id' => $publicado,
				'link_video_youtube' => $link_youtube,
				'descartado' => $data_excluido,
				'descartado_colaboradores_id  ' => $descartado_colaboradores_id,
				'palavras_escritor ' => $quantidade_palavras_original,
				'palavras_revisor ' => $quantidade_palavras_revisado,
				'palavras_narrador ' => $quantidade_palavras_revisado,
				'palavras_produtor ' => $quantidade_palavras_revisado,
				'criado ' => $data_criado,
				'atualizado ' => $data_maior
			];

			$this->db->table('artigos')->insert($data);
		}
	}
}
