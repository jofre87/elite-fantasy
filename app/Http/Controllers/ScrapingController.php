<?php

namespace App\Http\Controllers;


use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\EstadisticaTemporada;
use App\Models\EstadisticaPartidosRecientes;
use App\Models\RachaPuntos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class ScrapingController extends Controller
{

    public function scrapeQuotes(): JsonResponse
    {
        // Inicializar un cliente HTTP similar a un navegador
        $browser = new HttpBrowser(HttpClient::create());

        // Descargar y analizar el HTML de la página de destino
        $crawler = $browser->request('GET', 'https://www.futbolfantasy.com/analytics/laliga-fantasy/puntos');

        // Iterar sobre todos los jugadores
        $crawler->filter('.elemento_jugador')->each(function (Crawler $playerElement) {

            // Extraer el ID del jugador desde la clase CSS
            preg_match('/jugador-(\d+)/', $playerElement->attr('class'), $matches);
            $player_id = $matches[1] ?? null;

            if (!$player_id) {
                return; // Si no hay ID, saltamos este jugador
            }

            // Extraer información del jugador
            $name = $playerElement->attr('data-nombre') ?? 'Desconocido';
            $position = $playerElement->attr('data-posicion') ?? 'N/A';
            $team_id = $playerElement->attr('data-equipo') ?? 'N/A';
            $points_season = $playerElement->attr('data-puntostemporada') ?? 0;
            $points_1 = $playerElement->attr('data-puntos1') ?? 0;
            $points_3 = $playerElement->attr('data-puntos3') ?? 0;
            $points_5 = $playerElement->attr('data-puntos5') ?? 0;
            $average_points = $playerElement->attr('data-mediatemporada') ?? 0.0;
            $image_url = $playerElement->filter('.fotocontainer img')->attr('src') ?? '';

            // Buscar o crear el equipo
            $equipo = Equipo::updateOrCreate(
                ['id' => $team_id],
                ['nombre' => 'Equipo ' . $team_id, 'escudo' => 'https://static.futbolfantasy.com/uploads/images/cabecera/hd/' . $team_id . '.png']
            );
            // Crear o actualizar el jugador
            $jugador = Jugador::updateOrCreate(
                ['id' => $player_id],
                [
                    'nombre' => trim($name),
                    'posicion' => trim($position),
                    'equipo_id' => $equipo->id,
                    'imagen' => $image_url,
                    'ratio' => $playerElement->attr('data-ratio') ?? 0
                ]
            );

            // Crear o actualizar las estadísticas de temporada
            EstadisticaTemporada::updateOrCreate(
                ['jugador_id' => $jugador->id],
                [
                    'puntos_totales' => $points_season,
                    'media_puntos' => $average_points,
                    'partidos_jugados' => $playerElement->attr('data-temporada') ?? 0
                ]
            );

            // Crear o actualizar las estadísticas de partidos recientes
            foreach (['1', '3', '5'] as $rango) {
                // Modificar el valor de rango para que coincida con los valores definidos en el ENUM
                $rangoTexto = "{$rango} partido" . ($rango != '1' ? 's' : '');

                EstadisticaPartidosRecientes::updateOrCreate(
                    ['jugador_id' => $jugador->id, 'rango' => $rangoTexto],
                    [
                        'puntos' => $playerElement->attr("data-puntos{$rango}") ?? 0,
                        'partidos_jugados' => $playerElement->attr("data-jugados{$rango}") ?? 0,
                        'media' => $playerElement->attr("data-media{$rango}") ?? 0.0
                    ]
                );
            }

            // Crear o actualizar la racha de puntos
            for ($i = 1; $i <= 5; $i++) {
                $categoria = $this->getCategoria($playerElement->attr("data-racha{$i}"));
                RachaPuntos::updateOrCreate(
                    ['jugador_id' => $jugador->id, 'partido' => $i],
                    ['puntos' => $playerElement->attr("data-racha{$i}") ?? 0, 'categoria' => $categoria]
                );
            }
        });

        return response()->json(['message' => 'Datos actualizados correctamente']);
    }

    // Método para obtener la categoría de la racha
    private function getCategoria($puntos)
    {
        if ($puntos >= 15) {
            return 'very-high';
        } elseif ($puntos >= 10) {
            return 'high';
        } elseif ($puntos >= 5) {
            return 'medium';
        } elseif ($puntos == 0) {
            return 'zero';
        } else {
            return 'low';
        }
    }

}