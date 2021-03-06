﻿<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# Inclui funções e regras pre-determinadas
#

# inclui classe de conexão ao banco de dados
# include 'sql.connect.php';

# inclui funções de tratamento e manipulação para conexão do banco de dados
include 'phpSelectSQL.php';

#
# Fim de "Inclui funções e regras pre-determinadas"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # #  SELECT # # # # # # # # # # # # # # # 

/**
// modelo de informações mínimas vindas por $_POST para seleção do banco
**/
$_POST =
array(
    'type' => 'select',
    'table' => 'tabela',
    'select' => array(
        'segmento' => 'page'
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
$temp['select']['return'] = array('sku', 'values');

// # adiciona em @temp>select>return os campos para retorno do MySQL com os mesmos valores contidos em @post>select
// $temp['select']['return'] = $post['select'];

// # adiciona em @temp>select>return os campos para retorno do MySQL como string
// $temp['select']['return'] = 'sku';

# adiciona em @temp>select>regra>order>to o campo de definição para ordenação da tabela MySQL
$temp['select']['regra']['order']['to'] = 'index';

# adiciona em @temp>select>regra>order>by o valor de ordenação "ASC|DESC"
$temp['select']['regra']['order']['by'] = 'ASC';

# adiciona em @temp>select>regra>order>to o campo de definição para ordenação da tabela MySQL
// $temp['select']['regra']['order'][0]['to'] = 'index';

# adiciona em @temp>select>regra>order>by o valor de ordenação "ASC|DESC"
// $temp['select']['regra']['order'][0]['by'] = 'ASC';

# adiciona em @temp>select>regra>limit o valor de limite de respostas como "2"
$temp['select']['regra']['limit'] = '2';


# adiciona em $temp>select>connect>host o local do phpmyadmin
$temp['select']['connect']['host'] = 'localhost'; 

# adiciona em $temp>select>connect>host o usuario do servidor
$temp['select']['connect']['user'] = 'root'; 

# adiciona em $temp>select>connect>host a senha do servidor
$temp['select']['connect']['pasword'] = ''; 

# adiciona em $temp>select>connect>host o banco de dados
$temp['select']['connect']['database'] = 'meubanco'; 

# trata os parametros para chamada do banco
# # #

# adiciona em @temp>resposta os valores recebidos da função select atravez dos parametros em @temp>select
$temp['resposta'] = select($temp['select'], false);

print_r($temp['resposta']['result']);


# # #
# apaga itens usados na manipulação

# apaga @temp
unset($temp);

# apara @post
unset($post);

# apara _POST
unset($_POST);

# apaga itens usados na manipulação
# # #

# # # # # # # # # # # # # # #  SELECT # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # #  INSERT # # # # # # # # # # # # # # # 

/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/
$_POST =
array(
    'type' => 'insert',
    'table' => 'tabela',
    'select' => array(
        'segmento' => 'docentes',
        'grupo' => 'professor',
        'type' => 'Laurindo Furquim'
    )
);
/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/

# adiciona em @post os valores de _POST
$post = $_POST;


# # #
# trata dados de @post, de acordo com a estrutura do banco

# configurações de capturação de dados do banco
$temp['insert']['select']['regra']['limit'] = '1';
$temp['insert']['select']['regra']['order']['to'] = 'index';
$temp['insert']['select']['regra']['order']['by'] = 'DESC';
$temp['insert']['select']['where'] = $post['select'];
$temp['insert']['select']['table'] = $post['table'];
$temp['insert']['select']['return'] = 'index';
$temp['insert']['select']['change'] = select($temp['insert']['select'], false);
# configurações de capturação de dados do banco

# valida se não foi encontrado nem resultado
if ($temp['insert']['select']['change']['result']['length'] == 0) {

    # adiciona em @post>select>index o valor para index
    $post['select']['index'] = 1;
}

# valida se foi encontrado algum resultado
if ($temp['insert']['select']['change']['result']['length'] > 0) {

    # adiciona em @post>select>index o valor para index
    $post['select']['index'] = $temp['insert']['select']['change']['result']['0']['index']+1;
}

# fixa data atual
$temp['insert']['select']['date'] = date('Y-m-d h:i:s');

# cria MD5 para o valor atual
$temp['insert']['select']['sku'] = md5($post['select']['index'] . $post['select']['segmento'] . $post['select']['grupo'] . $post['select']['type'] . $temp['insert']['select']['date']);

# adiciona em @post>select>sku o resulado do md5 com 10 caracteres
$post['select']['sku'] = substr($temp['insert']['select']['sku'], 0, 5) . substr($temp['insert']['select']['sku'], -5);

# apaga @temp>insert>select
unset($temp['insert']['select']);

# trata dados de @post, de acordo com a estrutura do banco
# # #


# # #
# trata os parametros para a insersão no banco

# adiciona em @temp>insert>table o a tabela em @post
$temp['insert']['table'] = $post['table'];

# adiciona em @temp>insert>values os campos de redundanca de seleção
$temp['insert']['values'] = $post['select'];

// # adiciona em @temp>insert>values os campos de redundanca de seleção
// $temp['insert']['values'] = 'select';

# trata os parametros para a insersão no banco
# # #

# adiciona em @temp>resposta os resultados da inserção baseado nos dados de @temp>insert
$temp['resposta'] = insert($temp['insert'], false);

# trata os parametros para a insersão no banco
# # #

# # #
# apaga itens usados na manipulação

# apaga @temp
unset($temp);

# apara @post
unset($post);

# apara _POST
unset($_POST);

# apaga itens usados na manipulação
# # #

# # # # # # # # # # # # # # #  INSERT # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # #  UPDATE # # # # # # # # # # # # # # # 

/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/
$_POST =
array(
    'type' => 'update',
    'table' => 'tabela',
    'select' => array(
        'segmento' => 'docentes',
        'grupo' => 'professor',
        'type' => 'Laurindo Furquim'
    ),

    'update' => array(
        'values' => 'vai lá'
    )
);
/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/

# adiciona em @post os valores de _POST
$post = $_POST;


# # #
# trata os parametros para a insersão no banco

# adiciona em @temp>update>table o a tabela em @post
$temp['update']['table'] = $post['table'];

# adiciona em @temp>update>where os campos de redundanca de seleção
$temp['update']['where'] = $post['select'];

# adiciona em @temp>update>values os campos a serem atualizados
$temp['update']['values'] = $post['update'];

# adiciona em @temp>update>regra>order>to o campo de definição para ordenação da tabela MySQL
$temp['update']['regra']['order']['to'] = 'index';

# adiciona em @temp>update>regra>order>by o valor de ordenação "ASC|DESC"
$temp['update']['regra']['order']['by'] = 'ASC';

# adiciona em @temp>update>regra>limit o valor de limite de respostas como "2"
$temp['update']['regra']['limit'] = '1';

// # adiciona em @temp>update>values os campos de redundanca de seleção
// $temp['update']['values'] = 'select';


# adiciona em @temp>resposta os resultados da inserção baseado nos dados de @temp>insert
$temp['resposta'] = update($temp['update'], false);

# trata os parametros para a insersão no banco
# # #


# # #
# apaga itens usados na manipulação

# apaga @temp
unset($temp);

# apara @post
unset($post);

# apara _POST
unset($_POST);

# apaga itens usados na manipulação
# # #

# # # # # # # # # # # # # # #  UPDATE # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # #  DELETE # # # # # # # # # # # # # # # 

/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/
$_POST =
array(
    'type' => 'delete',
    'table' => 'tabela',
    'select' => array(
        'type' => 'Laurindo Furquim'
    )
);
/**
// modelo de informações mínimas vindas por $_POST para isersão do banco
**/

# adiciona em @post os valores de _POST
$post = $_POST;


# # #
# trata os parametros para a insersão no banco

# adiciona em @temp>delete>table o a tabela em @post
$temp['delete']['table'] = $post['table'];

# adiciona em @temp>delete>where os campos de redundanca de seleção
$temp['delete']['where'] = $post['select'];

# adiciona em @temp>delete>regra>limit o valor de limite de respostas como "2"
$temp['delete']['regra']['limit'] = 0;

// # adiciona em @temp>delete>values os campos de redundanca de seleção
// $temp['delete']['values'] = 'select';


# adiciona em @temp>resposta os resultados da inserção baseado nos dados de @temp>insert
$temp['resposta'] = delete($temp['delete'], false);

# trata os parametros para a insersão no banco
# # #

# # # # # # # # # # # # # # #  DELETE # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 


?>
