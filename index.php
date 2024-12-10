<?php
// URL da página de eventos
$url = "https://www.sympla.com.br/eventos?s=Tecnologia";

// Inicializa o cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar verificação SSL se necessário
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Seguir redirecionamentos
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');

// Executa a requisição
$html = curl_exec($ch);
curl_close($ch);

// Verifica se o HTML foi obtido com sucesso
if (!$html) {
    echo "Erro ao obter o HTML.";
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

foreach ($eventBlocks as $event) {
    // Extrai o nome do evento do atributo data-name
    $name = $event->getAttribute('data-name');

    // Extrai a URL da imagem
    $imageNode = $xpath->query('.//img', $event);
    $image = $imageNode->length > 0 ? $imageNode->item(0)->getAttribute('src') : 'Sem imagem';

    // Extrai o local
    $locationNode = $xpath->query('.//p[contains(@class, "pn67h1a")]', $event);
    $location = $locationNode->length > 0 ? trim($locationNode->item(0)->nodeValue) : 'Sem local';

    // Extrai a data
    $dateNode = $xpath->query('.//div[contains(@class, "qtfy413")]', $event);
    $date = $dateNode->length > 0 ? trim($dateNode->item(0)->nodeValue) : 'Sem data';

    // Exibe os resultados
    echo "<h3>$name</h3>";
    echo "<p><strong>Local:</strong> $location</p>";
    echo "<p><strong>Data:</strong> $date</p>";
    echo "<img src='$image' alt='$name' width='200'><br><br>";
}
?>
