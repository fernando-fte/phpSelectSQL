<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# Inclui funções e regras pre-determinadas
#

# inclui classe de conexão ao banco de dados
# include 'sql.connect.php';

# inclui funções de tratamento e manipulação para conexão do banco de dados
include 'sql.selector.php';

#
# Fim de "Inclui funções e regras pre-determinadas"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


/**
// modelo de informações mínimas vindas por $_POST para seleção do banco
**/
$_POST =
array(
    'type' => 'select',
    'table' => 'tabela',
    'select' => array(
        'segmento' => 'caso clinico',
        'type' => 'chamada'
    )
);
/**
// modelo de informações mínimas vindas por $_POST para seleção do banco
**/

# adiciona em @post os valores de _POST
$post = $_POST;

# # #
# trata os parametros para chamada do banco

# adiciona em @temp>select>table o a tabela em @post
$temp['select']['table'] = $post['table'];

# adiciona em @temp>select>where os campos de redundanca de seleção
$temp['select']['where'] = $post['select'];

# adiciona em @temp>select>return os campos para retorno do MySQL como uma lista array numérica
$temp['select']['return'] = array('index', 'sku');

// # adiciona em @temp>select>return os campos para retorno do MySQL com os mesmos valores contidos em @post>select
// $temp['select']['return'] = $post['select'];

// # adiciona em @temp>select>return os campos para retorno do MySQL como string
// $temp['select']['return'] = 'sku';

# adiciona em @temp>select>regra>order>to o campo de definição para ordenação da tabela MySQL
$temp['select']['regra']['order']['to'] = 'index';

# adiciona em @temp>select>regra>order>by o valor de ordenação "ASC|DESC"
$temp['select']['regra']['order']['by'] = 'ASC';

# adiciona em @temp>select>regra>limit o valor de limite de respostas como "2"
$temp['select']['regra']['limit'] = '2';

# trata os parametros para chamada do banco
# # #

# adiciona em @temp>resposta os valores recebidos da função select atravez dos parametros em @temp>select
$temp['resposta'] = select($temp['select'], true);

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
?>
