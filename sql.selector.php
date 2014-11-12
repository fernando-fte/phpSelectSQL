<?php
ini_set("display_errors", 0);

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# Inclui funções e regras pre-determinadas
#

# inclui classe de conexão ao banco de dados
include 'sql.connect.php';

# inclui funções de tratamento e manipulação para conexão do banco de dados
# include 'sql.selector1.php';

#
# Fim de "Inclui funções e regras pre-determinadas"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# adiciona em @post os valores de _POST
// $post = $_POST;

// modelo de solicitação vinda por post
$post['type'] = 'select';
$post['table'] = 'tabela';
$post['select']['segmento'] = 'caso clinico';
$post['select']['type'] = 'chamada';
// modelo de solicitação vinda por post


# # #
# trata os parametros para chamada do banco

# adiciona em @temp>select>table o a tabela em @post
$temp['select']['table'] = $post['table'];

# adiciona em @temp>select>where os campos de redundanca de seleção
$temp['select']['where'] = $post['where'];

# trata os parametros para chamada do banco
# # #

# adiciona em @temp>resposta os valores recebidos da função select atravez dos parametros em @temp>select
$temp['resposta'] = select($temp['select'], true);

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 

# # # # # # # # # # #
# Função: trata os valores para a função select ao banco mysql
function select($post, $print){

    # # # # #
    # # configura os valores de retorno

    # adiciona em @return>success com true, para iniciar as validações
    $return['success'] = true;

    # adiciona em @return>error>length o valor 0
    $return['error']['length'] = 0;

    # adiciona em @return>warning>length o valor 0
    $return['warning']['length'] = 0;

    # # configura os valores de retorno
    # # # # #


    # # # # #
    # # valida os valores recebidos em @post

    # declara @post>type como select
    $post['type'] = 'select';

    # adiciona em @temp>valida o valor recebido da função trata_query
    $temp['valida'] = trata_query($post, false);

    # verifica se houve algum problema no tratamento, com @temp>valida>successs sendo falso
    if ($temp['valida']['success'] == false) {

        # adiciona em @return>success com false, para abortar as validações
        $return['success'] = false;

        # adiciona em @return>error>[@~length]>type o um relato do que houve
        $return['error'][$return['error']['length']]['type'] = 'Houve algum erro na manipulação dos conteudos no momento da validação, consulte a lista de erros de "trata_query"';

        # adiciona +1 em $return>error>length
        $return['error']['length']++;

        # adiciona em @return>error>trata_query os valores de valida.
        $return['error']['process']['trata_query'] = $temp['valida'];
    }

    # verifica se o tratamento foi um successo, com @temp>valida>success sendo falso
    if ($temp['valida']['success'] == true) {

        # adiciona em @return>error>trata_query>success com verdadeiro.
        $return['error']['trata_query']['success'] = true;

        # verifica se existe algum alerta e adicioina a estrutura de @~trata_query
        if ($temp['valida']['warning']['length'] > 0) {

            # adiciona em @return>error>[@~length]>type o um relato do que houve
            $return['warning'][$return['error']['length']]['type'] = 'Algo não saiu como esperado e houve alguns alertas no processo "trata_query"';

            # adiciona +1 em $return>error>length
            $return['warning']['length']++;

            # adiciona os valores de @temp>valida>warning em @return>warning>trata_query
            $return['warning']['process']['trata_query'] = $temp['valida']['warning'];
        }
    }

    # # valida os valores recebidos em @post
    # # # # #


    # # # # #
    # # inicia tratamento dos valores recebidos

    # caso @return>success seja valido inicia a aplicação
    if ($return['success'] == true) {

        # para enviar ao query deve ficar desta forma
/*
$Sel = mysql_query(
    "SELECT `segmento`, `index`
    FROM `tabela` 
    WHERE `segmento` LIKE 'caso clinico' 
    AND `grupo` LIKE '1' 
    AND `type` LIKE 'image'
    ORDER BY `index` DESC
    LIMIT 3
") or die(mysql_error());
*/

        


    }

    # # inicia tratamento dos valores recebidos
    # # # # #


    # # # # # # # # #
    # # Finaliza exibindo o resultado
    # caso @print seja verdadeiro, exibe return
    if($print == true){

        # imprime na tela os valores de #return
        print_r($return);
    }

    # caso @print seja falso apenas retorna o valor
    if($print == false){

        # retorna o valor de @return
        return $return;
    }
    # # Finaliza exibindo o resultado
    # # # # # # # # #
}
# Função: trata os valores para a função select ao banco mysql
# # # # # # # # # # #

# # # # # # # # # # #
# Função: valida valores arrays e restrurura
function trata_query($post, $print){

    # # # # #
    # # configura os valores de retorno

    # adiciona em @return>success com true, para iniciar as validações
    $return['success'] = true;

    # adiciona em @return>error>length o valor 0
    $return['error']['length'] = 0;

    # adiciona em @return>warning>length o valor 0
    $return['warning']['length'] = 0;

    # adiciona em @return>backup os valores recebidos
    $return['backup'] = $post;

    # # configura os valores de retorno
    # # # # #

    # valida se não existe type em @post, identificando qual o tipo de solicitação
    if (!array_key_exists('type', $post)) {

        # adiciona em @return>error>[@~length]>type o um relato do que houve
        $return['error'][$return['error']['length']]['type'] = 'Não existe o type declarado, dessa forma não sera possivel preparar o conjunto array ou validalos.';

        # adiciona +1 em $return>error>length
        $return['error']['length']++;
    }

    # valida se existe type em @post, identificando qual o tipo de solicitação
    if (array_key_exists('type', $post)) {

        # valida não existe "table" em @post, com o valor da tabela a ser selecionada
        if (!array_key_exists('table', $post)) {

            # adiciona em @return>error>[@~length]>type o um relato do que houve
            $return['error'][$return['error']['length']]['type'] = 'Não existe tabela declarada na solicitação';

            # adiciona +1 em $return>error>length
            $return['error']['length']++;
        }


        # # #
        # caso @post>type seja do tipo "select"
        if ($post['type'] == 'select') {
            
            # #
            # valida os campos não configuraveis internamente

            # valida se existe em @post a arary where, que deve conter todos os dados
            if (array_key_exists('where', $post)) {
                
                # valida se @post>where não possui elementos arrays
                if (!is_array($post['where'])){

                    # valida se @post>where não possui os valores do tipo false
                    if ($post['where'] != false) {

                        # adiciona em @return>error>[@~length]>type o um relato do que houve
                        $return['error'][$return['error']['length']]['type'] = 'O conteúdo de where esta incompatível, era esperado arrays ou bolean:false';

                        # adiciona +1 em $return>error>length
                        $return['error']['length']++;
                    }

                    # adiciona em @return>warning>[@~length]>type o um relato do que houve
                    $return['warning'][$return['warning']['length']]['type'] = 'Não foi passado nem um parametro array, isso pode acarretar um excesso na memória por exibir todos os resultados';

                    # adiciona +1 em $return>error>length
                    $return['warning']['length']++;
                }
            }

            # valida se não existe em @post a arary where, que deve conter todos os dados
            if (!array_key_exists('where', $post)){

                # adiciona em @return>error>[@~length]>type o um relato do que houve
                $return['error'][$return['error']['length']]['type'] = 'Não existe o grupo where que contem os valores de afunilamento na seleção do banco';

                # adiciona +1 em $return>error>length
                $return['error']['length']++;
            }

            # valida os campos não configuraveis internamente
            # #


            # # 
            # valida os campos que podem ser reconfigurados conforme um padrão

            # valida se não existe "regra" em @post, deve conter as configurações a mais
            if (!array_key_exists('regra', $post)) {

                # define @post>regra>relative como false
                $post['regra']['relative'] = false;

                # define @post>regra>order como false
                $post['regra']['order'] = false;

                # define @post>regra>limine com 1
                $post['regra']['limit'] = "1";


                # adiciona em @return>warning>[@~length]>type o um relato do que houve
                $return['warning'][$return['warning']['length']]['type'] = 'Não foi passado nem um parametro regra, assim sendo definido todos com as configurações padrões ';

                # adiciona +1 em $return>error>length
                $return['warning']['length']++;
            }

            # valida se existe "regra" em @post, deve conter as configurações a mais
            if (array_key_exists('regra', $post)) {

                # valida se não existe "relative" em @post>regra
                if (!array_key_exists('relative', $post['regra'])) {

                    # define @post>regra>relative como false
                    $post['regra']['relative'] = false;


                    # adiciona em @return>warning>[@~length]>type o um relato do que houve
                    $return['warning'][$return['warning']['length']]['type'] = 'Não foi definido a regra para busca relativa ou especifica, assim por padrão ficando estabelecida como especifica';

                    # adiciona +1 em $return>error>length
                    $return['warning']['length']++;
                }

                # valida se não existe "order" em @post>regra, que deve conter as regras de ordenação
                if (!array_key_exists('order', $post['regra'])) {

                    # define @post>regra>order como false
                    $post['regra']['order'] = false;


                    # adiciona em @return>warning>[@~length]>type o um relato do que houve
                    $return['warning'][$return['warning']['length']]['type'] = 'Não foi encontrado o parametro "order", assim defininco com sem ordenação para seleção';

                    # adiciona +1 em $return>error>length
                    $return['warning']['length']++;
                }

                # valida se existe "order" em @post>regra, que deve conter as regras de ordenação
                if (array_key_exists('order', $post['regra'])) {

                    # valida se existe "to" em @post>regra>order, que identifica um campo para setar a ordem ede exibição
                    if (array_key_exists('to', $post['regra']['order'])) {

                        # define @post>regra>order como false
                        $post['regra']['order'] = false;


                        # adiciona em @return>warning>[@~length]>type o um relato do que houve
                        $return['warning'][$return['warning']['length']]['type'] = 'Não foi encontrado um dos parametros de seleção de ordem, assim definindo como sem ordenamento para exibição';

                        # adiciona +1 em $return>error>length
                        $return['warning']['length']++;
                    }

                    # valida se existe "by" em @post>regra>order, define se a ordem é acendente ou descendente
                    if (array_key_exists('by', $post['regra']['order'])) {

                        # define @post>regra>order como false
                        $post['regra']['order'] = false;


                        # adiciona em @return>warning>[@~length]>type o um relato do que houve
                        $return['warning'][$return['warning']['length']]['type'] = 'Não foi encontrado um dos parametros de seleção de ordem, assim definindo como sem ordenamento para seleção';

                        # adiciona +1 em $return>error>length
                        $return['warning']['length']++;
                    }
                }

                # valida se não existe "limit" em @post>regra, deve conter as regras de quantos itens a ser encontrados
                if (!array_key_exists('limit', $post['regra'])) {

                    # define @post>regra>limit como 1
                    $post['regra']['limit'] = "1";


                    # adiciona em @return>warning>[@~length]>type o um relato do que houve
                    $return['warning'][$return['warning']['length']]['type'] = ' Não foi definido a regra para limite de impressão da busca do sql, assim sendo setada com limite igual a 1 (um)';

                    # adiciona +1 em $return>error>length
                    $return['warning']['length']++;
                }

                # valida se existe "limit" em @post>regra, deve conter as regras de quantos itens a ser encontrados 
                if (array_key_exists('limit', $post['regra'])) {

                    # padroniza o tamanho de @post>regra>limit caso seja menor que zero
                    if ($post['regra']['limit'] <= 0) {

                        # define @post>regra>limit com false
                        $post['regra']['limit'] = false;
                    }
                }
            }

            # valida se não existe @post>return, contem a lista de campos a serem retornados
            if (!array_key_exists('return', $post)) {

                # adiciona em @post>regra a array como lista com valor 1
                $post['return'] = array("*");


                # adiciona em @return>warning>[@~length]>type o um relato do que houve
                $return['warning'][$return['warning']['length']]['type'] = 'Não foi definido os campos a serem retornados, assim sendo setado com um retorno de todos os campos da tabela selecionada';

                # adiciona +1 em $return>error>length
                $return['warning']['length']++;
            }

            # valida os campos que podem ser reconfigurados conforme um padrão
            # # 
        }
        # caso @post>type seja do tipo "select"
        # # #

        # # #
        # caso @post>type seja do tipo "select"
        else if ($post['type'] == 'update') {
            
            # inicia validação conforme os valore
        }
        # caso @post>type seja do tipo "select"
        # # #

        # # #
        # caso @post>type seja do tipo "select"
        else if ($post['type'] == 'insert') {
            
            # inicia validação conforme os valore
        }
        # caso @post>type seja do tipo "select"
        # # #

        # # #
        # caso @post>type não esteja na lista de validação
        else {

            # adiciona em @return>error>[@~length]>type o um relato do que houve
            $return['error'][$return['error']['length']]['type'] = 'O valor de validação deve ser "select", "update" ou "inset")';

            # adiciona +1 em $return>error>length
            $return['error']['length']++;
        }
        # caso @post>type não esteja na lista de validação
        # # #
    }

    # adiciona em @return>result o resultado da compilação
    $return['result'] = $post;

    # # # # #
    # # Finializa validação

    # valida se @return>error>length é maior que 0
    if ($return['error']['length'] > 0) {

        # adiciona em @return>success com bolean:false
        $return['success'] = false;
    }
    # # Finializa validação
    # # # # #

    # # # # # # # # #
    # # Finaliza exibindo o resultado
    # caso @print seja verdadeiro, exibe return
    if ($print == true) {
 
        # imprime na tela os valores de #return
        print_r($return);
    }

    # caso @print seja falso apenas retorna o valor
    if ($print == false) {

        # retorna o valor de @return
        return $return;
    }
    # # Finaliza exibindo o resultado
    # # # # # # # # #
}
# Função: valida valores arrays e restrurura
# # # # # # # # # # #



/*
# abre conexao
$conecta->AbreConexao();

# seta o valor query
$Sel = mysql_query(
    "SELECT `segmento`, `index`
    FROM `tabela` 
    WHERE `segmento` LIKE 'caso clinico' 
    AND `grupo` LIKE '1' 
    AND `type` LIKE 'image'
    ORDER BY `index` DESC
    LIMIT 3
") or die(mysql_error());

# fecha a conexao
$conecta->FechaConexao();

# laço para processar e atriubir dentro de value os resultados do banco
while ($val = mysql_fetch_array($Sel)) {

    print_r($val);
}


print_r(array("a", "b", "c"));
*/

/*
# # # # # # # # # #
# forma esperada

$temp['select']['return'] = string | array("a", "b", "c")
$temp['select']['tabela'] = string
$temp['select']['where']['relative'] = bolean | true = array("a", "b", "c")
$temp['select']['where']['contents'] = array("select" => "val", "grupo" => "1")
$temp['select']['regra']['limit'] = {0,1,2,3, [...]};
$temp['select']['regra']['order']['to'] = string;
$temp['select']['regra']['order']['by'] = ASC | DESC;

# forma esperada
# # # # # # # # # #

# # # # # # # # # #
# forma reduzido

$temp['return']
$temp['tabela']
$temp['relative']
$temp['where']
$temp['limit']
$temp['order']['to']
$temp['order']['by']

function(temp)
# forma reduzido
# # # # # # # # # #
*/



# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # Função de tratamento para valores do tipo 'select'
// function select($temp){

//     # valida itens necessarios
//     if(array_key_exists('key', $temp)){

//     }

// }
# # # Função de tratamento para valores do tipo 'select'
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # #
# função para validar se as entradas do $_POST são corretas
// function valida_select($temp) {

//     # #
//     # quando não existir a array "regra", adiciona as configurações necessárias
//     if (!array_key_exists("regra", $temp)) {

//         # adiciona na array @temp>regra>where o valor que a busca é fixa (LIKE | LIKE%)
//         $temp['regra']['where'] = "LIKE";
        
//         # adiciona na array @temp>regra>limit que a resposta do servidor será apenas '1'
//         $temp['regra']['limit'] = "1";

//         # adiciona na array @temp>order>to que a busca sera ordenada em "index"
//         $temp['regra']['order']['to'] = "index";

//         # adiciona na array @temp>order>by que a busca será ordenada do menor para o maior
//         $temp['regra']['order']['by'] = "ASC";
//     }
//     # Fim da 'quando não existir a array "regra", adiciona as configurações necessárias'
//     # #

//     # #
//     # trata elementos caso exista a array "regra", valida cada item dentro da mesma 
//     else {
//         # #
//         # valida "where" que estabelece a seleção
//         if(!array_key_exists("where", $temp['regra'])) {

//             # adiciona na array @temp>regra>where o valor que a busca é fixa (LIKE | LIKE%)
//             $temp['regra']['where'] = "LIKE";
//         }
//         # Fim de 'valida "where" que estabelece a seleção'
//         # #

//         # #
//         # valida se há "order" que estabelece a ordem das respostas (que a coluna "X" exibida de 0 -> ∞ ou ∞ -> 0)
//         if(!array_key_exists("order", $temp['regra'])) {

//             # adiciona na array @temp>order>to que a busca sera ordenada em "index"
//             $temp['regra']['order']['to'] = "index";

//             # adiciona na array @temp>order>by que a busca será ordenada do menor para o maior
//             $temp['regra']['order']['by'] = "ASC";
//         }
//         # Fim de 'valida se há "order" que estabelece a ordem das respostas (que a coluna "X" exibida de 0 -> ∞ ou ∞ -> 0)'
//         # #

//         # #
//         # valida se os valores de "order" estão corretos
//         else { 

//             # #
//             # valida se há "order>to"
//             if(!array_key_exists("to", $temp['regra']['order'])) {

//                 # adiciona na array @temp>order>to que a busca sera ordenada em "index"
//                 $temp['regra']['order']['to'] = "index";
//             }
//             # Fim de 'valida se há "order>to"'
//             # #

//             # #
//             # valida se há "order>by"
//             if(!array_key_exists("by", $temp['regra']['order'])) {

//                 # adiciona na array @temp>order>by que a busca será ordenada do menor para o maior
//                 $temp['regra']['order']['by'] = "ASC";
//             }
//             # Fim de 'valida se há "order>by"'
//             # #
//         }
//         # Fim de "valida se os valores de "order" estão corretos"
//         # #

//         # #
//         # valida se há "limit", e este define quantos resultados o banco deve retornar
//         if(!array_key_exists("limit", $temp['regra'])) {

//             # adiciona na array @temp>regra>limit que a resposta do servidor será apenas '1'
//             $temp['regra']['limit'] = "1";
//         }
//         # valida se há "limit", e este define quantos resultados o banco deve retornar
//         # #
//     }
//     # Fim de 'trata elementos caso exista a array "regra", valida cada item dentro da mesma '
//     # #

//     # #
//     # valida a array "status" existe
//     if(!array_key_exists("status", $temp)){

//         # determina que "status" é falso
//         $temp['status'] = false;
//     }
//     # Fim de 'valida a array "status"'
//     # #

//     # retorna $temp para a função
//     return $temp;
// }
# função para validar se as entradas do $_POST são corretas
# # # # #

?>
