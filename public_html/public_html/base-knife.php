<?php
require('config.php');

$steamID = '76561198366109879'; // Substitua pelo SteamID da conta vendedora
$appID = 730; // AppID do CSGO
$contextID = 2; // ContextID do CSGO
$currency = 7; // Código da moeda (7 = BRL)
$cacheTime = 3600; // Tempo de cache em segundos (1 hora)

// Diretório de cache (certifique-se de que tenha permissões de escrita)
$cacheDir = __DIR__ . '/cache/';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

// Função para obter o preço do item com cache
function getMarketPrice($itemName, $currency, $appID, $cacheDir, $cacheTime) {
    // Gera um nome de arquivo baseado no nome do item
    $cacheFile = $cacheDir . md5($itemName) . '.json';

    // Verifica se o cache existe e se ainda é válido
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        // Retorna o conteúdo do cache
        $cachedData = json_decode(file_get_contents($cacheFile), true);
        return $cachedData;
    }

    // Faz a requisição à Steam Market API
    $marketUrl = "https://steamcommunity.com/market/priceoverview/?currency=$currency&appid=$appID&market_hash_name=" . urlencode($itemName);
    $marketResponse = file_get_contents($marketUrl);
    $marketData = json_decode($marketResponse, true);

    // Verifica se a resposta contém o preço
    if (isset($marketData['lowest_price'])) {
        // Salva o resultado no cache
        file_put_contents($cacheFile, json_encode($marketData));
        return $marketData;
    } else {
        return null; // Retorna nulo se o preço não estiver disponível
    }
}

// Função para limpar e converter o preço
function cleanPrice($priceString) {
    // Remove o símbolo "R$ " e converte para número
    $cleanedPrice = str_replace(['R$', '.', ','], ['', '', '.'], $priceString); // Remove os pontos e vírgulas corretamente
    return floatval($cleanedPrice);
}

// Obtenha o inventário
$url = "https://steamcommunity.com/inventory/$steamID/$appID/$contextID";
$response = file_get_contents($url);
$inventory = json_decode($response, true);

// Verifica se o inventário foi carregado corretamente
if (isset($inventory['assets'])) {
    // Lista de itens com suas descrições
    $descriptions = $inventory['descriptions'];

    echo "<h2>Facas disponíveis para venda:</h2><div class='inventory-items'>";

    // Itera sobre os itens do inventário
    foreach ($inventory['assets'] as $item) {
        $assetID = $item['assetid']; // Obtenção do assetID do item
        // Encontra a descrição do item correspondente
        foreach ($descriptions as $desc) {
            if ($desc['classid'] === $item['classid'] && $desc['instanceid'] === $item['instanceid']) {
                $itemName = $desc['market_hash_name'];
                $itemIcon = $desc['icon_url'];
                $itemType = $desc['type'];
                $tradable = $desc['tradable']; // Se o item é negociável
                $tradeRestrictionDays = isset($desc['market_tradable_restriction']) ? $desc['market_tradable_restriction'] : 0; // Dias de restrição de troca
                $itemTags = isset($desc['tags']) ? $desc['tags'] : []; // Pegando os tags do item

                // Verificar se o item tem o marcador 'knife'
                $isKnife = false;
                foreach ($itemTags as $tag) {
                    if (strtolower($tag['localized_tag_name']) == 'knife') {
                        $isKnife = true;
                        break;
                    }
                }

                // Se o item for uma faca, exibi-lo
                if ($isKnife) {
                    // Obtém o preço do mercado usando o cache
                    $marketData = getMarketPrice($itemName, $currency, $appID, $cacheDir, $cacheTime);

                    if ($marketData && isset($marketData['lowest_price'])) {
                        // Limpa e converte o preço do mercado
                        $marketPrice = cleanPrice($marketData['lowest_price']);
                        
                        // Calcula o preço para a loja (85% do valor de mercado)
                        $storePrice = $marketPrice * 0.85;

                        // Exibe os itens com o layout do card
                        echo "
                        <div class='card product-card'>
                            <div class='card-header'>
                                <span class='delivery-tag'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-star-fill' viewBox='0 0 16 16'>
                                        <path d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/>
                                    </svg> Entrega Imediata
                                </span>
                                <h5 class='price-real'>R$ " . number_format($storePrice, 2, ',', '.') . "</h5>
                                <p class='price-steam'>Preço Steam: R$ " . number_format($marketPrice, 2, ',', '.') . "</p>
                            </div>
                            <img src='https://steamcommunity-a.akamaihd.net/economy/image/$itemIcon' alt='$itemName' class='product-image'>
                            <div class='card-body'>
                                <h5 class='product-section'>$itemType</h5>
                                <p class='product-name'>$itemName</p>
                                <p class='product-condition'>Nova de Fábrica</p>
                                <button class='btn btn-primary btn-add-to-cart'>Adicionar ao Carrinho</button>
                            </div>
                        </div>";
                    }
                }
                break;
            }
        }
    }

    echo "</div>";
} else {
    echo "Não foi possível carregar o inventário.";
}
?>
<?php

ini_set('display_errors', 1); // Habilita a exibição de erros
ini_set('display_startup_errors', 1); // Habilita a exibição de erros de inicialização
error_reporting(E_ALL); // Define o nível de relatório de erros para mostrar todos os erros


require('config.php');

$steamID = '76561198366109879'; // Substitua pelo SteamID da conta vendedora
$appID = 730; // AppID do CSGO
$contextID = 2; // ContextID do CSGO
$currency = 7; // Código da moeda (7 = BRL)
$cacheTime = 3600; // Tempo de cache em segundos (1 hora)

// Diretório de cache (certifique-se de que tenha permissões de escrita)
$cacheDir = __DIR__ . '/cache/';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

// Função para obter o preço do item com cache
function getMarketPrice($itemName, $currency, $appID, $cacheDir, $cacheTime) {
    // Gera um nome de arquivo baseado no nome do item
    $cacheFile = $cacheDir . md5($itemName) . '.json';

    // Verifica se o cache existe e se ainda é válido
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        // Retorna o conteúdo do cache
        $cachedData = json_decode(file_get_contents($cacheFile), true);
        return $cachedData;
    }

    // Faz a requisição à Steam Market API
    $marketUrl = "https://steamcommunity.com/market/priceoverview/?currency=$currency&appid=$appID&market_hash_name=" . urlencode($itemName);
    $marketResponse = file_get_contents($marketUrl);
    $marketData = json_decode($marketResponse, true);

    // Verifica se a resposta contém o preço
    if (isset($marketData['lowest_price'])) {
        // Salva o resultado no cache
        file_put_contents($cacheFile, json_encode($marketData));
        return $marketData;
    } else {
        return null; // Retorna nulo se o preço não estiver disponível
    }
}

// Função para limpar e converter o preço
function cleanPrice($priceString) {
    // Remove o símbolo "R$ " e converte para número
    $cleanedPrice = str_replace(['R$', '.', ','], ['', '', '.'], $priceString); // Remove os pontos e vírgulas corretamente
    return floatval($cleanedPrice);
}

// Obtenha o inventário
$url = "https://steamcommunity.com/inventory/$steamID/$appID/$contextID";
$response = file_get_contents($url);
$inventory = json_decode($response, true);

// Verifica se o inventário foi carregado corretamente
if (isset($inventory['assets'])) {
    // Lista de itens com suas descrições
    $descriptions = $inventory['descriptions'];

    echo "<h2>Facas disponíveis para venda:</h2><div class='inventory-items'>";

    // Itera sobre os itens do inventário
    foreach ($inventory['assets'] as $item) {
        $assetID = $item['assetid']; // Obtenção do assetID do item
        // Encontra a descrição do item correspondente
        foreach ($descriptions as $desc) {
            if ($desc['classid'] === $item['classid'] && $desc['instanceid'] === $item['instanceid']) {
                $itemName = $desc['market_hash_name'];
                $itemIcon = $desc['icon_url'];
                $itemType = $desc['type'];
                $tradable = $desc['tradable']; // Se o item é negociável
                $tradeRestrictionDays = isset($desc['market_tradable_restriction']) ? $desc['market_tradable_restriction'] : 0; // Dias de restrição de troca
                $itemTags = isset($desc['tags']) ? $desc['tags'] : []; // Pegando os tags do item

                // Verificar se o item tem o marcador 'knife'
                $isKnife = false;
                foreach ($itemTags as $tag) {
                    if (strtolower($tag['localized_tag_name']) == 'knife') {
                        $isKnife = true;
                        break;
                    }
                }

                // Se o item for uma faca, exibi-lo
                if ($isKnife) {
                    // Obtém o preço do mercado usando o cache
                    $marketData = getMarketPrice($itemName, $currency, $appID, $cacheDir, $cacheTime);

                    if ($marketData && isset($marketData['lowest_price'])) {
                        // Limpa e converte o preço do mercado
                        $marketPrice = cleanPrice($marketData['lowest_price']);
                        
                        // Calcula o preço para a loja (85% do valor de mercado)
                        $storePrice = $marketPrice * 0.85;

                        // Exibe os itens com o layout do card
                        echo "
                        <div class='card product-card'>
                            <div class='card-header'>
                                <span class='delivery-tag'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-star-fill' viewBox='0 0 16 16'>
                                        <path d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/>
                                    </svg> Entrega Imediata
                                </span>
                                <h5 class='price-real'>R$ " . number_format($storePrice, 2, ',', '.') . "</h5>
                                <p class='price-steam'>Preço Steam: R$ " . number_format($marketPrice, 2, ',', '.') . "</p>
                            </div>
                            <img src='https://steamcommunity-a.akamaihd.net/economy/image/$itemIcon' alt='$itemName' class='product-image'>
                            <div class='card-body'>
                                <h5 class='product-section'>$itemType</h5>
                                <p class='product-name'>$itemName</p>
                                <p class='product-condition'>Nova de Fábrica</p>
                                <button class='btn btn-primary btn-add-to-cart'>Adicionar ao Carrinho</button>
                            </div>
                        </div>";
                    }
                }
                break;
            }
        }
    }

    echo "</div>";
} else {
    echo "Não foi possível carregar o inventário.";
}
?>


<html>
    <head>
            <link href="css/index.css" rel="stylesheet">

    </head>
    <body>
        
    </body>
</html>