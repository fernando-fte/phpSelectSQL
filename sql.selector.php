﻿<?php
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


# # # # # # # # # # #
# Função: solicita ao MySQL os valores conforme os parametros

function query($post, $print){

    # # # # #
    # # configura os valores de retorno

    # adiciona em @return>success com true, para iniciar as validações
    $return['success'] = true;

    # adiciona em @return>error>length o valor 0
    $return['error']['length'] = 0;

    # adiciona em @return>warning>length o valor 0
    $return['warning']['length'] = 0;

    # adiciona em @return>backup os valores de @post
    $return['backup'] = $post;

    # define @return>process

    # # configura os valores de retorno
    # # # # #


    # # # # #
    # # inicia capturação dos dados no banco
    
    # valida se não existe type em @post, identificando qual o tipo de solicitação
    if (!array_key_exists('type', $post)) {

        # adiciona em @return>error>[@~length]>type o um relato do que houve
        $return['error'][$return['error']['length']]['type'] = 'Não existe o type declarado, dessa forma não sera possivel preparar os valores para seleção do banco';

        # adiciona +1 em $return>error>length
        $return['error']['length']++;
    }

    # valida se existe type em @post, identificando qual o tipo de solicitação
    if (array_key_exists('type', $post)) {

        # caso @post>type seja do tipo "query", inicia as seleções
        if ($post['type'] == 'query') {

            # adiciona em @temp>connect>mysql os dados de conexão do servidor MySQL
            $temp['connect']['mysql'] = mysql_connect('localhost', 'root', '');

            # valida se @temp>connect>msyql não estabeleceu conexão
            if (mysql_error() != false) {

                # adiciona em @return>error>[@~length]>type o um relato do que houve
                $return['error'][$return['error']['length']]['type'] = 'Não foi possivel connectar ao MySQL, verifique os dados de conexão';

                # adiciona +1 em $return>error>length
                $return['error']['length']++;
            }

            # valida se @temp>connect>msyql estabeleceu conexão
            if (mysql_error() == false) {

                # adiciona em @temp>connect>banco a conxão com o banco
                $temp['connect']['banco'] = mysql_select_db('meubanco', $temp['connect']['mysql']);

                # valida se @temp>connect>banco não foi conectado ou encontrado
                if (mysql_error() != false) {

                    # adiciona em @return>error>[@~length]>type o um relato do que houve
                    $return['error'][$return['error']['length']]['type'] = 'Não foi possivel conectar-se ao banco, verifique os dados de conexão';

                    # adiciona +1 em $return>error>length
                    $return['error']['length']++;
                }

                # valida se @temp>connect>banco foi conectado ou encontrado
                if (mysql_error() == false) {

                    # adiciona em query os valores para a função mysql_query()
                    mysql_query("SET NAMES 'utf8'");
                    mysql_query('SET character_set_connection=utf8');
                    mysql_query('SET character_set_client=utf8');
                    mysql_query('SET character_set_results=utf8');

                    # adiciona em @temp>connect>result o resultado da insersão dos paramestros no banco
                    $temp['connect']['result'] = mysql_query($post['sql']);
                    // $temp['connect']['result'] = mysql_query("SELECT `sku`, `values` FROM `tabela` WHERE `segmento` LIKE 'page' ORDER BY `index` ASC LIMIT 2");
                    // $temp['connect']['result'] = mysql_query("INSERT INTO `tabela` (`segmento`, `index`, `grupo`, `type`, `values`, `sku`) VALUES ('teste', '1', NULL, NULL, 't', '58f2f5cf43')");
                    // $temp['connect']['result'] = mysql_query("DELETE FROM `tabela` WHERE `sku` LIKE '58f2f5cf43'");
                    // $temp['connect']['result'] = mysql_query("UPDATE `tabela` SET `grupo` = 'b', `type` = 'b' WHERE `sku` = '58f2f5cf43' LIMIT 1");

                    # valida se @temp>connect>result não conseguiu ser executado
                    if (mysql_error() != false) {

                        # adiciona em @return>error>[@~length]>type o um relato do que houve
                        $return['error'][$return['error']['length']]['type'] = 'Algo de inesperado aconteceu, verifique as informações em erro->log';

                        # adiciona em @return>error>[@~length]>log o erro da função mysql_error()
                        $return['error'][$return['error']['length']]['log'] = mysql_error();

                        # adiciona +1 em $return>error>length
                        $return['error']['length']++;
                    }

                    # valida se @temp>connect>result conseguiu ser executado
                    if (mysql_error() == false) {

                            $return['result']['num'] = mysql_num_rows($temp['connect']['result']);
                            $return['result']['assoc'] = mysql_fetch_assoc($temp['connect']['result']);
                            $return['result']['all'] = $temp['connect']['result'];

                            if (mysql_fetch_assoc($temp['connect']['result']) == false) {

                                # explode valores de @temp>connect>result
                                $return['result']['num'] = mysql_num_rows($temp['connect']['result']);
                                $return['result']['assoc'] = mysql_fetch_assoc($temp['connect']['result']);
                                $return['result']['all'] = $temp['connect']['result'];
                            }

                            if (mysql_fetch_assoc($temp['connect']['result']) == true) {

                                # explode valores de @temp>connect>result
                                echo 'a';
                            }

                    }
                }

                # adiciona em @temp>connect>close o fechamento da conexão com MySQL
                $temp['connect']['close'] = mysql_close($temp['connect']['mysql']);
            }
        }

        # caso @post>type não seja do tipo "query", reeencaminha as validações conforme os atributos corretos
        if (!$post['type'] == 'query') {

            # encaminhas valores conforme as funções de chamada
            switch ($post['type']) {

                # caso @post>type seja "select"
                case 'select':

                    # adiciona em @return os valores de resposta vindos da função select()
                    $return = select($post, false);
                    break;

                # caso @post>type seja "insert"
                case 'insert':

                    # adiciona em @return os valores de resposta vindos da função insert()
                    $return = insert($post, false);
                    break;

                # caso @post>type seja "update"
                case 'update':

                    # adiciona em @return os valores de resposta vindos da função update()
                    $return = update($post, false);
                    break;
                
                default:

                    # adiciona em @return>error>[@~length]>type o um relato do que houve
                    $return['error'][$return['error']['length']]['type'] = 'O valor declarado em type não coincide com nem uma ação definida, podendo ser ("query", "insert", "update", "select")';

                    # adiciona +1 em $return>error>length
                    $return['error']['length']++;
                    break;
            }
        }
    }

    # # inicia capturação dos dados no banco
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

# Função: solicita ao MySQL os valores conforme os parametros
# # # # # # # # # # #

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

    # adiciona em @return>backup os valores de @post
    # $return['backup'] = $post;

    # define @return>process

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
        $return['process']['trata_query']['error'] = $temp['valida'];
    }

    # verifica se o tratamento foi um successo, com @temp>valida>success sendo falso
    if ($temp['valida']['success'] == true) {

        # define @post com os valores recebidos em @temp>valida>result
        $post = $temp['valida']['result'];


        # adiciona em @return>error>trata_query>success com verdadeiro.
        $return['process']['trata_query']['success'] = true;

        # verifica se existe algum alerta e adicioina a estrutura de @~trata_query
        if ($temp['valida']['warning']['length'] > 0) {

            # adiciona em @return>error>[@~length]>type o um relato do que houve
            $return['warning'][$return['error']['length']]['type'] = 'Algo não saiu como esperado e houve alguns alertas no processo "trata_query"';

            # adiciona +1 em $return>error>length
            $return['warning']['length']++;

            # adiciona os valores de @temp>valida>warning em @return>warning>trata_query
            $return['process']['trata_query']['warning'] = $temp['valida']['warning'];
        }
    }

    # apaga @temp>valida
    unset($temp['valida']);

    # # valida os valores recebidos em @post
    # # # # #


    # # # # #
    # # inicia tratamento dos valores recebidos

    # caso @return>success seja valido inicia a aplicação
    if ($return['success'] == true) {

        # # # # #
        # # configura os valores de retorno

        # # #
        # define valores em @return>process>montagem para montagem

        # define @return>process>montagem>success com false, a espera de montagem
        $return['process']['montagem']['success'] = false;

        # define @return>process>montagem>error como NULL para sem erros
        $return['process']['montagem']['error'] = null;


        # define @return>process>montagem>warning como NULL para sem alertas
        $return['process']['montagem']['warning'] = null;
        # # #

        # #
        # define valores em @return>process>montagem para montagem

        # define @return>process>montagem>success com false, a espera de montagem
        $return['process']['select']['success'] = false;

        # define @return>process>montagem>error como NULL para sem erros
        $return['process']['select']['error'] = null;


        # define @return>process>montagem>warning como NULL para sem alertas
        $return['process']['select']['warning'] = null;
        # # #

        # # configura os valores de retorno
        # # # # #


        # # # # #
        # Inicia montagem dos valores para "SELECT"

        # valida @return>process>montagem>success é "false", inicia montagem de select
        if ($return['process']['montagem']['success'] == false) {


            # declara em @temp>montagem>sql o inicio dos parametros de syntax de seleção tipo "SELEC" para o MySQL
            $temp['montagem']['sql'] = 'SELECT ';


            # # # #
            # # tratamento para "SELECT"

            # valida se existe apenas um resultado em @post>return, adicionando apenas o valor entre aspas
            if ($post['return']['length'] == 1) {

                # adiciona em @temp>montagem>sql o valor de @post>return na posição atual, sem virgula com espaço ao final
                $temp['montagem']['sql'] .= '`'. $post['return']['0'].'` ';
            }

            # valida se existe mais de um resultado em @post>return, adicionando os valores sequenciados por virgula
            if ($post['return']['length'] > 1) {

                # adiciona @temp>montagem>select>count com valor 0
                $temp['montagem']['select']['count'] = 0;

                # inicia loop para selecionar cada valor em @post>return
                while ($temp['montagem']['select']['count'] < $post['return']['length']) {

                    # verifica se @temp>montagem>select>count esta na primeira posição em 0
                    if (($temp['montagem']['select']['count']) == 0) {

                        # adiciona em @temp>montagem>sql o valor de @post>return na posição atual, sem virgula sem espaço ao final
                        $temp['montagem']['sql'] .= '`'. $post['return'][$temp['montagem']['select']['count']].'`';
                    }

                    # verifica se @temp>montagem>select>count passou da primeira posição em 0
                    if ($temp['montagem']['select']['count'] > 0) {

                        # adiciona em @temp>montagem>sql o valor de @post>return na posição atual, com virgula e espaço ao final
                        $temp['montagem']['sql'] .= ', `'. $post['return'][$temp['montagem']['select']['count']].'` ';
                    }

                    # adiciona +1 no contador de @temp>montagem>select>count
                    $temp['montagem']['select']['count']++;
                }

                # apaga @temp>montagem>select
                unset($temp['montagem']['select']);
            }

            # # tratamento para "SELECT"
            # # # #


            # # # #
            # # tratamento para "TABLE"

            # adiciona em @temp>montagem>sql o o parametro para tabela
            $temp['montagem']['sql'] .= 'FROM `'.$post['table'].'` ';

            # # tratamento para "TABLE"
            # # # #


            # # # #
            # # tratamento para "WHERE"

            # adiciona @temp>montagem>select>count com valor 0
            $temp['montagem']['where']['count'] = 0;

            # desmonta os valores de @post>where para @temp>montagem>where key e val
            foreach ($post['where'] as $temp['montagem']['where']['key'] => $temp['montagem']['where']['val']) {

                # verifica se @temp>montagem>select>count esta na primeira sequencia de chave
                if ($temp['montagem']['where']['count'] == 0) {

                    # adiciona em @temp>montagem>sql a abertura da solicitação do tipo WHERE
                    $temp['montagem']['sql'] .= 'WHERE ';
                }

                # verifica se @temp>montagem>select>count passou da primeira sequencia de chave
                if ($temp['montagem']['where']['count'] > 0) {

                    # adiciona em @temp>montagem>sql a abertura da solicitação do tipo AND
                    $temp['montagem']['sql'] .= 'AND ';
                }

                # adiciona em @temp>montagem>sql o valor de @post>where para coluna de "SELECT WHERE"
                $temp['montagem']['sql'] .= '`'. $temp['montagem']['where']['key'].'`';

                # valida se o @post>regra>relative é verdadero pra valor relativo
                if ($post['regra']['relative'] == true) {

                    # adiciona em @temp>montagem>sql o valor de @post>where para coluna de "SELECT LIKE", para valores relativos
                    $temp['montagem']['sql'] .= ' LIKE \'%'.$temp['montagem']['where']['val'].'%\' ';
                }

                # valida se o @post>regra>relative é falso pra valor relativo, e verdadeiro para especifico
                if ($post['regra']['relative'] == false) {

                    # adiciona em @temp>montagem>sql o valor de @post>where para coluna de "SELECT LIKE", para valores especificos
                    $temp['montagem']['sql'] .= ' LIKE \''.$temp['montagem']['where']['val'].'\' ';
                }

                # adiciona +1 no contador de @temp>montagem>select>count
                $temp['montagem']['where']['count']++;
            }

            # apaga @temp>montagem>where
            unset($temp['montagem']['where']);

            # # tratamento para "WHERE"
            # # # #


            # # # #
            # # tratamento para "ORDER"

            # valida se @post>regra>order não é falso
            if ($post['regra']['order'] != false) {

                # adiciona em @temp>montagem>sql o o parametro para "ORDER BY" quanto a coluna
                $temp['montagem']['sql'] .= 'ORDER BY `'.$post['regra']['order']['to'].'` ';

                # adiciona em @temp>montagem>sql o o parametro para "ORDER BY" quanto a ordem
                $temp['montagem']['sql'] .= $post['regra']['order']['by'].' ';
            }

            # # tratamento para "ORDER"
            # # # #


            # # # #
            # # tratamento para "LIMITE"

            # adiciona em @temp>montagem>sql o o parametro para "LIMIT" em @post>regra>limit
            $temp['montagem']['sql'] .= 'LIMIT '.$post['regra']['limit'];

            # # tratamento para "LIMITE"
            # # # #


            # # # #
            # # finaliza tratamentos

            # define @return>process>montagem>success como "true", para concluido
            $return['process']['montagem']['success'] = true;

            # adiciona em @return>process>montagem>result o valor de @temp>montagem>sql
            $return['process']['montagem']['result'] = $temp['montagem']['sql'];

            # apaga @temp>montagem
            unset($temp['montagem']);

            # # finaliza tratamentos
            # # # #
        }

        # # Inicia montagem dos valores para select
        # # # # #


        # # # # #
        # # Inicia montagem dos valores para select

        # valida @return>process>montagem>success é "true" para o tratamento dos parametros "SELECT"
        if ($return['process']['montagem']['success'] == true) {

            # adiciona em @temp>select>sql o valor do resultado em @return>montagem>result
            $temp['select']['sql'] = $return['process']['montagem']['result'];

            # adiciona a propriedade "query" em @temp>select>type
            $temp['select']['type'] = 'query';

            # adiciona em @temp>select>result as respostas do servidor e define impressão como falsa
            $temp['select']['result'] = query($temp['select'], true);


        }

        # # Inicia montagem dos valores para select
        # # # # #
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

                    # valida se não existe "to" em @post>regra>order, que identifica um campo para setar a ordem ede exibição
                    if (!array_key_exists('to', $post['regra']['order'])) {

                        # define @post>regra>order como false
                        $post['regra']['order'] = false;


                        # adiciona em @return>warning>[@~length]>type o um relato do que houve
                        $return['warning'][$return['warning']['length']]['type'] = 'Não foi encontrado um dos parametros de seleção de ordem, assim definindo como sem ordenamento para exibição';

                        # adiciona +1 em $return>error>length
                        $return['warning']['length']++;
                    }

                    # valida se não existe "by" em @post>regra>order, define se a ordem é acendente ou descendente
                    if (!array_key_exists('by', $post['regra']['order'])) {

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

            # valida se existe @post>return, contem a lista de campos a serem retornados
            if (array_key_exists('return', $post)) {

                # valida se existe array em @post>return
                if (is_array($post['return'])) {

                    # valida se existe algo na posição zero, adiciona a contagen lenght
                    if (array_key_exists('0', $post['return'])) {

                        # adiciona em @post>return>length o valor da função count(), referente a quantia de valores na posição
                        $post['return']['length'] = count($post['return']);
                    }

                    # valida se não existe algo na posição zero
                    if (!array_key_exists('0', $post['return'])) {

                        # define em @post>return com a função array_keys() os keys das arrays em lista numerica
                        $post['return'] = array_keys($post['return']);

                        # adiciona em @post>return>length o valor da função count(), referente a quantia de valores na posição
                        $post['return']['length'] = count($post['return']);
                    }
                }

                # valida se não existe array em @post>return
                if (!is_array($post['return'])) {

                    # valida se o conteudo de @post>return não é vazio
                    if ($post['return'] != '') {

                        # define array em @post>return na posição zero contendo @post>return
                        $post['return'] = array($post['return']);

                        # adiciona em @post>return>length o valor 1, referente a quantia de valores na posição
                        $post['return']['length'] = 1;


                        # adiciona em @return>warning>[@~length]>type o um relato do que houve
                        $return['warning'][$return['warning']['length']]['type'] = 'Não foi definido nem um campo array para a seleção do banco, desta forma por padrão foi declarado na posição zero a string repassada';

                        # adiciona +1 em $return>error>length
                        $return['warning']['length'] = 1;
                    }

                    # valida se o conteudo de @post>return é vazio
                    if ($post['return'] == '') {

                        # adiciona em @return>error>[@~length]>type o um relato do que houve
                        $return['error'][$return['error']['length']]['type'] = 'Não foi encontrado nem um valor referente a seleção para o banco, defina ao menos um ou apenas não declare esta propriedade';

                        # adiciona +1 em $return>error>length
                        $return['error']['length']++;
                    }
                }
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
