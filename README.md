### Documentação do Projeto: Sympla Event Scraper

---

#### **Repositório no GitHub**
**Repositório**: [Skylinenando/Sympla-Event-Scraper](https://github.com/Skylinenando/Sympla-Event-Scraper)

---

### **Descrição**

Este script PHP faz o scraping de eventos da página de resultados do Sympla com base em uma busca específica. Ele extrai informações relevantes, como o nome do evento, imagem, local e data, exibindo esses dados em uma página HTML.

---

### **Pré-requisitos**

- **Servidor Web** (Apache, Nginx, etc.)
- **PHP** versão 7.4 ou superior
- **Extensões PHP**:
  - `cURL`
  - `libxml` (para `DOMDocument` e `DOMXPath`)

---

### **Instalação**

1. Clone o repositório:
   ```bash
   git clone https://github.com/Skylinenando/Sympla-Event-Scraper.git
   ```

2. Navegue até o diretório do projeto:
   ```bash
   cd Sympla-Event-Scraper
   ```

3. Certifique-se de que as extensões `cURL` e `libxml` estão habilitadas em seu `php.ini`.

4. Execute o script em seu servidor local ou através da linha de comando:
   ```bash
   php sympla_scraper.php
   ```

---

### **Código-Fonte**

```php
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
    // Extrai o nome do evento
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
```

---

### **Funcionamento**

1. **Busca por Eventos**:
   - O script faz uma requisição `GET` para a URL do Sympla com o termo de busca `Tecnologia`.

2. **Extração de Dados**:
   - Utiliza `DOMDocument` e `DOMXPath` para analisar o HTML e buscar os elementos relevantes:
     - **Nome do Evento** (`data-name`)
     - **Imagem** (`<img>`)
     - **Local** (`<p class="pn67h1a">`)
     - **Data** (`<div class="qtfy413">`)

3. **Exibição dos Resultados**:
   - Imprime os dados dos eventos em HTML formatado.

---

### **Execução**

- Coloque o arquivo em seu servidor web e acesse via navegador:
  ```
  http://localhost/sympla_scraper.php
  ```

- Ou execute diretamente no terminal:
  ```bash
  php sympla_scraper.php
  ```

---

### **Possíveis Problemas**

- **Erro na Requisição**:
  - Verifique sua conexão com a internet e a URL do Sympla.
  - Certifique-se de que `cURL` está habilitado.

- **Mudanças na Estrutura HTML**:
  - O Sympla pode alterar a estrutura da página, exigindo ajustes nos seletores XPath.

---

### **Licença**

Este projeto é licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

---

### **Contato**

Para sugestões ou problemas, entre em contato através do GitHub:

**Perfil**: [Skylinenando](https://github.com/Skylinenando)
