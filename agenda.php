<?php
require 'vendor/autoload.php';

// Caminho para o arquivo do Certificado Raiz do CA (cacert.pem)
$caCertPath = 'cacert.pem'; // Substitua pelo caminho real para o seu arquivo cacert.pem

// Configurar as credenciais da Conta de Serviço
$client = new Google_Client();

// Configure o certificado raiz do CA no cliente Google
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => false, // Desativa a verificação do certificado SSL
]));

$client->setApplicationName('My First Project');
$client->setScopes(array(Google_Service_Calendar::CALENDAR));
$client->setAuthConfig('keys.json');
$client->setAccessType('offline');
$client->getAccessToken();
$client->getRefreshToken();

// Inicializar o serviço Google Agenda
$service = new Google_Service_Calendar($client);

// ID da Agenda específica
$calendarId = ''; // Substitua pelo ID da sua agenda

// Parâmetros da consulta para listar eventos
$optParams = array(
    'maxResults' => 10, // Número máximo de eventos a serem listados
    'orderBy' => 'startTime',
    'singleEvents' => true,
    //'timeMin' => date('c'),
    'timeMin' => '2023-01-01T00:00:00Z', // Data e hora de início mínima
    'timeMax' => '2023-12-31T23:59:59Z', // Data e hora de término máxima
);

// Liste os eventos
try {
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();

    // Exiba os eventos
    if (empty($events)) {
        print "Nenhum evento encontrado.\n";
    } else {
        print "Próximos eventos:\n";
        foreach ($events as $event) {
            $start = $event->start->dateTime;
            if (empty($start)) {
                $start = $event->start->date;
            }
            $summary = $event->getSummary();
            echo '<div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">';
            echo "<strong>$summary</strong><br>";
            echo "Data e Hora: $start<br>";
            echo '<a href="' . $event->htmlLink . '" target="_blank">Ver no Google Agenda</a>';
            echo '</div>';
        }

        //print_r($events[0]);
    }
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
