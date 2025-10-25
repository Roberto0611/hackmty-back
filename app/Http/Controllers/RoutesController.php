<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\LocationService\LocationServiceClient;
use Aws\Credentials\Credentials;
use Exception;

class RoutesController extends Controller
{
    private $locationClient;
    private $calculatorName;

    /**
     * Constructor para inicializar el cliente de AWS
     */
    public function __construct()
    {
        // Es una buena práctica verificar que las variables de entorno existan
        if (!env('AWS_ACCESS_KEY_ID') || !env('AWS_SECRET_ACCESS_KEY') || !env('AWS_DEFAULT_REGION') || !env('AWS_ROUTE_CALCULATOR_NAME')) {
            // Manejar error de configuración
            throw new Exception("Faltan variables de entorno de AWS.");
        }

        $credentials = new Credentials(
            env('AWS_ACCESS_KEY_ID'),
            env('AWS_SECRET_ACCESS_KEY')
        );

        $this->locationClient = new LocationServiceClient([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION'),
            'credentials' => $credentials
        ]);
        
        $this->calculatorName = env('AWS_ROUTE_CALCULATOR_NAME');
    }

    /**
     * Obtiene la ruta entre dos puntos.
     */
    public function getRoute(Request $request)
    {
        // Validar la entrada (puedes usar el validador de Laravel)
        $validated = $request->validate([
            'from_lat' => 'required|numeric',
            'from_lng' => 'required|numeric',
            'to_lat'   => 'required|numeric',
            'to_lng'   => 'required|numeric',
            // Aceptamos mayúsculas o minúsculas y las normalizamos abajo
            'mode'     => 'sometimes|string|in:Car,Truck,Walking,car,truck,walking'
        ]);

        // IMPORTANTE: AWS Location usa el formato [Longitud, Latitud]
        $departurePosition = [(float)$validated['from_lng'], (float)$validated['from_lat']];
        $destinationPosition = [(float)$validated['to_lng'], (float)$validated['to_lat']];

        try {
            // Normalizar TravelMode para que coincida exactamente con los valores esperados por AWS
            $modeInput = $validated['mode'] ?? 'Walking';
            $mode = match (strtolower($modeInput)) {
                'car' => 'Car',
                'truck' => 'Truck',
                'walking' => 'Walking',
                default => 'Walking',
            };

            $result = $this->locationClient->calculateRoute([
                'CalculatorName'      => $this->calculatorName,
                'DeparturePosition'   => $departurePosition,
                'DestinationPosition' => $destinationPosition,
                'TravelMode'          => $mode, // 'Car', 'Truck' o 'Walking'
                'RouteOptimization'   => 'Fastest', // 'Fastest' (predeterminado) o 'Shortest'
                'IncludeLegGeometry'  => true, // Descomenta si necesitas la polilínea para dibujar en un mapa
            ]);

            // El resultado principal está en 'Summary'
            $summary = $result->get('Summary');

            return response()->json([
                'distance_km'     => $summary['Distance'], // Distancia en km
                'duration_seconds' => $summary['DurationSeconds'], // Duración en segundos
                'route_summary'    => $summary, // Devuelve todo el resumen
                // 'route_data' => $result->toArray() // Descomenta para ver la respuesta completa
            ]);

        } catch (Exception $e) {
            // Manejar errores de la API de AWS
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}