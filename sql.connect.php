<?php
# # # #
# inicia classe _conecta
class connectMySQL{

    # # # #
    # configuração de coneção com o servidor

    var $ip = 'localhost'; # configura local do phpmyadmin
    var $user = 'root'; # usuario do servidor
    var $pass = ''; # senha do servidor
    var $database = 'meubanco'; # banco de dados

    # Fim de "configuração de coneção com o servidor"
    # # # #

    # abre conexão ao servidor
    function open() {
        $this->conn = mysql_connect($this->ip, $this->user, $this->pass) or die ( '<h1>erro ao selecionar Banco de dados</h1>' );
        mysql_select_db($this->database, $this->conn) or die ( '<h1>erro ao selecionar Tabela</h1>' );

        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
    }

    # fecha conexão
    function close() {
        mysql_close($this->conn);
    }
}
# Fim de "inicia classe _conecta"
# # # #
?>
