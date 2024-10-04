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

    echo "<h2>Itens disponíveis para venda:</h2><div class='inventory-items'>";

    // Itera sobre os itens do inventário
    foreach ($inventory['assets'] as $item) {
        // Encontra a descrição do item correspondente
        foreach ($descriptions as $desc) {
            if ($desc['classid'] === $item['classid'] && $desc['instanceid'] === $item['instanceid']) {
                $itemName = $desc['market_hash_name'];
                $itemIcon = $desc['icon_url'];
                $itemType = $desc['type'];
                $tradable = $desc['tradable']; // Se o item é negociável
                $tradeRestrictionDays = isset($desc['market_tradable_restriction']) ? $desc['market_tradable_restriction'] : 0; // Dias de restrição de troca
                $itemTags = isset($desc['tags']) ? $desc['tags'] : []; // Pegando os tags do item

                // Obtém o preço do mercado usando o cache
                $marketData = getMarketPrice($itemName, $currency, $appID, $cacheDir, $cacheTime);

                if ($marketData && isset($marketData['lowest_price'])) {
                    // Limpa e converte o preço do mercado
                    $marketPrice = cleanPrice($marketData['lowest_price']);
                    
                    // Calcula o preço para a loja (85% do valor de mercado)
                    $storePrice = $marketPrice * 0.85;

                    // Exibe os itens com preços
                    echo "
                    <div class='item'>
                        <img src='https://steamcommunity-a.akamaihd.net/economy/image/$itemIcon' alt='$itemName'>
                        <p>Nome: $itemName</p>
                        <p>Tipo: $itemType</p>
                        <p>Preço no Mercado: R$ " . number_format($marketPrice, 2, ',', '.') . "</p>
                        <p>Preço na Loja: R$ " . number_format($storePrice, 2, ',', '.') . "</p>";

                    // Verifica se o item é negociável ou está restrito
                    if ($tradable == 1) {
                        echo "<p>Status: Disponível para troca</p>";
                    } else {
                        // Se houver restrição de dias para troca
                        echo "<p>Status: Não disponível para troca. Faltam $tradeRestrictionDays dias para estar disponível.</p>";
                    }

                    // Exibe os marcadores/tags do item
                    if (!empty($itemTags)) {
                        echo "<p>Marcadores:</p><ul>";
                        foreach ($itemTags as $tag) {
                            echo "<li>" . $tag['localized_tag_name'] . "</li>";
                        }
                        echo "</ul>";
                    }

                    echo "</div>";
                } else {
                    // Caso o preço não esteja disponível
                    echo "
                    <div class='item'>
                        <img src='https://steamcommunity-a.akamaihd.net/economy/image/$itemIcon' alt='$itemName'>
                        <p>Nome: $itemName</p>
                        <p>Tipo: $itemType</p>
                        <p>Preço no Mercado: Não disponível</p>";

                    // Verifica se o item é negociável ou está restrito
                    if ($tradable == 1) {
                        echo "<p>Status: Disponível para troca</p>";
                    } else {
                        echo "<p>Status: Não disponível para troca. Faltam $tradeRestrictionDays dias para estar disponível.</p>";
                    }

                    // Exibe os marcadores/tags do item mesmo que o preço não esteja disponível
                    if (!empty($itemTags)) {
                        echo "<p>Marcadores:</p><ul>";
                        foreach ($itemTags as $tag) {
                            echo "<li>" . $tag['localized_tag_name'] . "</li>";
                        }
                        echo "</ul>";
                    }

                    echo "</div>";
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





<style>
.inventory-items {
    display: flex;
    flex-wrap: wrap;
}

.item {
    width: 150px;
    margin: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
}

.item img {
    width: 100%;
    height: auto;
}

.item p {
    margin: 5px 0;
}

.item ul {
    list-style-type: none;
    padding: 0;
}

.item ul li {
    background-color: #eee;
    padding: 5px;
    margin: 5px 0;
    border-radius: 3px;
}
</style>


