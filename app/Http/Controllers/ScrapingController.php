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

        // Descargar y analizar el HTML de la página de mercado (NUEVO)
        $crawlerMercado = $browser->request('GET', 'https://www.futbolfantasy.com/analytics/laliga-fantasy/mercado');
        $valoresMercado = [];

        // Extraer valores de mercado
        $crawlerMercado->filter('.elemento_jugador')->each(function (Crawler $playerElement) use (&$valoresMercado) {
            $player_id = $playerElement->attr('data-nombre') ?? null;
            if ($player_id) {
                $valoresMercado[$player_id] = [
                    'valor_actual' => $playerElement->attr('data-valor') ?? 0,
                    'diferencia' => $playerElement->attr('data-diferencia1') ?? 0
                ];
            }
        });

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
            $average_points = $playerElement->attr('data-mediatemporada') ?? 0.0;
            $image_url = $playerElement->filter('.fotocontainer img')->attr('data-src')
                ?? $playerElement->filter('.fotocontainer img')->attr('src')
                ?? '';


            // Extraer valores de mercado (si existen)
            $valor_actual = $valoresMercado[$name]['valor_actual'] ?? 0;
            $diferencia = $valoresMercado[$name]['diferencia'] ?? 0;

            // Extraer el nombre del equipo
            $team_name = $playerElement->filter('.equipo span')->count() > 0
                ? trim($playerElement->filter('.equipo span')->text())
                : "Equipo $team_id";

            // Extraer la racha de puntos como un array
            $rachaPuntos = [];
            $playerElement->filter('.racha-box.columna_puntos')->each(function (Crawler $puntoElement) use (&$rachaPuntos) {
                $puntos = trim($puntoElement->text());
                if (is_numeric($puntos)) {
                    $rachaPuntos[] = (int) $puntos;
                }
            });

            // Buscar o crear el equipo
            $equipo = Equipo::updateOrCreate(
                ['id' => $team_id],
                ['nombre' => $team_name, 'escudo' => "https://static.futbolfantasy.com/uploads/images/cabecera/hd/{$team_id}.png"]
            );
            // Crear o actualizar el jugador
            $jugador = Jugador::updateOrCreate(
                ['id' => $player_id],
                [
                    'nombre' => trim($name),
                    'posicion' => trim($position),
                    'equipo_id' => $equipo->id,
                    'imagen' => $image_url,
                    'ratio' => $playerElement->attr('data-ratio') ?? 0,
                    'valor_actual' => $valor_actual,
                    'diferencia' => $diferencia
                ]
            );

            // Crear o actualizar las estadísticas de temporada
            EstadisticaTemporada::updateOrCreate(
                ['jugador_id' => $jugador->id],
                [
                    'puntos_totales' => $points_season,
                    'media_puntos' => $average_points,
                    'partidos_jugados' => $playerElement->attr('data-temporada') ?? 0,
                    'racha_puntos' => json_encode($rachaPuntos) // Guardar racha como JSON
                ]
            );


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