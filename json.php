<?php
// URL da página de eventos
$url = "https://www.sympla.com.br/eventos?s=Tecnologia";

// Inicializa o cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');

// Executa a requisição
$html = curl_exec($ch);
curl_close($ch);

// Verifica se o HTML foi obtido com sucesso
if (!$html) {
    echo json_encode(["error" => "Erro ao obter o HTML."]);
    exit;
}

// Carrega o HTML com DOMDocument
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

// Inicializa XPath para consultar elementos
$xpath = new DOMXPath($dom);

// Seleciona os links dos eventos
$eventBlocks = $xpath->query('//a[contains(@class, "sympla-card")]');

// Array para armazenar os dados dos eventos
$events = [];

foreach ($eventBlocks as $event) {
    // Extrai o link do evento e corrige barras invertidas
    $link = str_replace('\/', '/', $event->getAttribute('href'));

    // Extrai o nome do evento
    $name = $event->getAttribute('data-name');

    // Extrai a URL da imagem e corrige barras invertidas
    $imageNode = $xpath->query('.//img', $event);
    $image = $imageNode->length > 0 ? str_replace('\/', '/', $imageNode->item(0)->getAttribute('src')) : 'Sem imagem';

    // Extrai o local
    $locationNode = $xpath->query('.//p[contains(@class, "pn67h1a")]', $event);
    $location = $locationNode->length > 0 ? trim($locationNode->item(0)->nodeValue) : 'Sem local';

    // Extrai a data
    $dateNode = $xpath->query('.//div[contains(@class, "qtfy413")]', $event);
    $date = $dateNode->length > 0 ? trim($dateNode->item(0)->nodeValue) : 'Sem data';

    // Adiciona os dados ao array de eventos
    $events[] = [
        "name" => $name,
        "location" => $location,
        "date" => $date,
        "image" => $image,
        "link" => $link
    ];
}

// Converte os eventos em JSON e corrige barras invertidas globalmente
$jsonData = json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$jsonData = str_replace('\/', '/', $jsonData);

// Salva o JSON em um arquivo chamado eventos.json
$filePath = 'eventos.json';
if (file_put_contents($filePath, $jsonData)) {
    echo "Arquivo JSON salvo com sucesso em: $filePath";
} else {
    echo "Erro ao salvar o arquivo JSON.";
}
?>
